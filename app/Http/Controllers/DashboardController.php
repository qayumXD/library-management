<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        if (auth()->user()->isAdmin()) {
            // Admin dashboard data
            $data = [
                'totalBooks' => Book::count(),
                'availableBooks' => Book::where('status', 'available')->count(),
                'totalMembers' => User::where('role', 'student')->count(),
                'activeMembers' => User::where('role', 'student')
                    ->whereHas('borrows', function ($query) {
                        $query->whereNull('returned_at');
                    })
                    ->count(),
                'totalBorrows' => Borrow::count(),
                'overdueBooks' => Borrow::whereNull('returned_at')
                    ->where('due_date', '<', now())
                    ->count(),
                'recentBorrows' => Borrow::with(['book', 'user'])
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        } else {
            // Student dashboard data
            $data = [
                'myBorrows' => Borrow::with('book')
                    ->where('user_id', auth()->id())
                    ->latest()
                    ->take(5)
                    ->get(),
            ];
        }

        return view('dashboard', $data);
    }
} 