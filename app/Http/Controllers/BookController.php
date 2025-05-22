<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Category;
use App\Models\Author;

class BookController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin')->except(['available', 'show']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with(['category', 'authors'])->latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    public function available()
    {
        $books = Book::whereDoesntHave('borrows', function ($query) {
            $query->whereNull('returned_at');
        })->with(['category', 'authors'])->latest()->paginate(10);
        
        return view('books.available', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        $authors = Author::all();
        return view('books.create', compact('categories', 'authors'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book = Book::create($validated);
        $book->authors()->attach($request->authors);

        return redirect()->route('books.index')
            ->with('success', 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        $book->load(['category', 'authors', 'borrows' => function ($query) {
            $query->whereNull('returned_at');
        }]);
        return view('books.show', compact('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        $authors = Author::all();
        return view('books.edit', compact('book', 'categories', 'authors'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'isbn' => 'required|string|max:13|unique:books,isbn,' . $book->id,
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'authors' => 'required|array',
            'authors.*' => 'exists:authors,id',
            'quantity' => 'required|integer|min:1',
        ]);

        $book->update($validated);
        $book->authors()->sync($request->authors);

        return redirect()->route('books.index')
            ->with('success', 'Book updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        $book->delete();
        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully.');
    }
}
