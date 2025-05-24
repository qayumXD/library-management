<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\Borrow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBooks = Book::count();
        
        // Calculate available books by joining with borrows and using group by
        $availableBooks = DB::table('books')
            ->leftJoin('borrows', function($join) {
                $join->on('books.id', '=', 'borrows.book_id')
                    ->whereNull('borrows.returned_at');
            })
            ->select(DB::raw('SUM(books.quantity) - COUNT(borrows.id) as available_count'))
            ->value('available_count') ?? 0;
            
        $totalMembers = Member::count();
        $activeMembers = Member::where('status', 'active')->count();
        $totalBorrows = Borrow::count();
        $overdueBooks = Borrow::whereNull('returned_at')
            ->where('due_date', '<', now())
            ->count();
        $recentBorrows = Borrow::with(['book', 'member'])
            ->latest('borrowed_at')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalBooks',
            'availableBooks',
            'totalMembers',
            'activeMembers',
            'totalBorrows',
            'overdueBooks',
            'recentBorrows'
        ));
    }
} 