<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['index', 'request', 'reserve', 'return']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = auth()->user()->isAdmin()
            ? Borrow::with(['book', 'user'])->latest()->paginate(10)
            : Borrow::where('user_id', auth()->id())->with('book')->latest()->paginate(10);

        return view('borrows.index', compact('borrows'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get books that have available copies (quantity > active borrows)
        $books = Book::whereExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('books as b')
                ->whereColumn('b.id', 'books.id')
                ->whereRaw('b.quantity > (
                    SELECT COUNT(*) 
                    FROM borrows 
                    WHERE borrows.book_id = b.id 
                    AND borrows.returned_at IS NULL
                )');
        })->get();

        $members = Member::where('status', 'active')->get();
        return view('borrows.create', compact('books', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ]);

        // Check if the book is actually available
        $book = Book::findOrFail($validated['book_id']);
        $activeBorrows = Borrow::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->count();

        if ($activeBorrows >= $book->quantity) {
            return back()->withErrors(['book_id' => 'This book is no longer available.']);
        }

        $borrow = Borrow::create([
            'book_id' => $validated['book_id'],
            'member_id' => $validated['member_id'],
            'borrowed_at' => now(),
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null
        ]);

        return redirect()->route('borrows.show', $borrow)
            ->with('success', 'Book borrowed successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        $borrow->load(['book', 'member']);
        return view('borrows.show', compact('borrow'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrow $borrow)
    {
        if ($borrow->returned_at) {
            return redirect()->route('borrows.show', $borrow)
                ->with('error', 'Cannot edit a returned borrow.');
        }

        $books = Book::whereExists(function ($query) use ($borrow) {
            $query->select(DB::raw(1))
                ->from('books as b')
                ->whereColumn('b.id', 'books.id')
                ->whereRaw('b.quantity > (
                    SELECT COUNT(*) 
                    FROM borrows 
                    WHERE borrows.book_id = b.id 
                    AND borrows.returned_at IS NULL
                    AND borrows.id != ?
                )', [$borrow->id]);
        })->get();

        $members = Member::where('status', 'active')->get();
        return view('borrows.edit', compact('borrow', 'books', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrow $borrow)
    {
        if ($borrow->returned_at) {
            return redirect()->route('borrows.show', $borrow)
                ->with('error', 'Cannot update a returned borrow.');
        }

        $validated = $request->validate([
            'book_id' => 'required|exists:books,id',
            'member_id' => 'required|exists:members,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string'
        ]);

        // Check if the book is actually available (excluding current borrow)
        if ($validated['book_id'] != $borrow->book_id) {
            $book = Book::findOrFail($validated['book_id']);
            $activeBorrows = Borrow::where('book_id', $book->id)
                ->whereNull('returned_at')
                ->count();

            if ($activeBorrows >= $book->quantity) {
                return back()->withErrors(['book_id' => 'This book is no longer available.']);
            }
        }

        $borrow->update([
            'book_id' => $validated['book_id'],
            'member_id' => $validated['member_id'],
            'due_date' => $validated['due_date'],
            'notes' => $validated['notes'] ?? null
        ]);

        return redirect()->route('borrows.show', $borrow)
            ->with('success', 'Borrow updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        if ($borrow->returned_at) {
            return redirect()->route('borrows.show', $borrow)
                ->with('error', 'Cannot delete a returned borrow.');
        }

        $borrow->delete();
        return redirect()->route('borrows.index')
            ->with('success', 'Borrow deleted successfully.');
    }

    /**
     * Mark a book as returned.
     */
    public function return(Borrow $borrow)
    {
        // Check if user is authorized to return this book
        if (!auth()->user()->isAdmin() && $borrow->user_id !== auth()->id()) {
            abort(403);
        }

        // Update borrow record
        $borrow->update([
            'returned_at' => now(),
        ]);

        // Increase book quantity
        $borrow->book->increment('quantity');

        return back()->with('success', 'Book returned successfully.');
    }

    public function request(Book $book)
    {
        // Check if book is available
        if ($book->quantity <= 0) {
            return back()->with('error', 'Book is not available for borrowing.');
        }

        // Check if user already has a pending request for this book
        $existingRequest = Borrow::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->whereNull('approved_at')
            ->first();

        if ($existingRequest) {
            return back()->with('error', 'You already have a pending request for this book.');
        }

        // Create borrow request
        Borrow::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'requested_at' => now(),
        ]);

        return back()->with('success', 'Book borrow request submitted successfully.');
    }

    public function reserve(Book $book)
    {
        // Check if book is currently borrowed
        $isBorrowed = Borrow::where('book_id', $book->id)
            ->whereNull('returned_at')
            ->exists();

        if (!$isBorrowed) {
            return back()->with('error', 'Book is available for borrowing. No need to reserve.');
        }

        // Check if user already has a pending reservation
        $existingReservation = Borrow::where('user_id', auth()->id())
            ->where('book_id', $book->id)
            ->whereNull('returned_at')
            ->whereNull('approved_at')
            ->first();

        if ($existingReservation) {
            return back()->with('error', 'You already have a pending reservation for this book.');
        }

        // Create reservation
        Borrow::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'requested_at' => now(),
            'is_reservation' => true,
        ]);

        return back()->with('success', 'Book reservation submitted successfully.');
    }

    public function approve(Borrow $borrow)
    {
        $book = $borrow->book;

        // Check if book is still available
        if ($book->quantity <= 0) {
            return back()->with('error', 'Book is no longer available.');
        }

        // Update borrow record
        $borrow->update([
            'approved_at' => now(),
            'borrowed_at' => now(),
            'due_date' => now()->addDays(14), // 2 weeks borrowing period
        ]);

        // Decrease book quantity
        $book->decrement('quantity');

        return back()->with('success', 'Borrow request approved successfully.');
    }

    public function reject(Borrow $borrow)
    {
        $borrow->update([
            'rejected_at' => now(),
        ]);

        return back()->with('success', 'Borrow request rejected successfully.');
    }
}
