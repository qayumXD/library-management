<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard route accessible by all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Student routes
    Route::middleware(['role:student'])->group(function () {
        Route::get('/books/available', [BookController::class, 'available'])->name('books.available');
        Route::post('/books/{book}/borrow', [BorrowController::class, 'request'])->name('books.borrow.request');
        Route::post('/books/{book}/reserve', [BorrowController::class, 'reserve'])->name('books.reserve');
        Route::post('/books/{book}/return', [BorrowController::class, 'return'])->name('books.return');
    });

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        // Books
        Route::resource('books', BookController::class);
        
        // Categories
        Route::resource('categories', CategoryController::class);
        
        // Authors
        Route::resource('authors', AuthorController::class);
        
        // Members
        Route::resource('members', MemberController::class);
        
        // Borrows
        Route::resource('borrows', BorrowController::class);
        Route::post('/borrows/{borrow}/approve', [BorrowController::class, 'approve'])->name('borrows.approve');
        Route::post('/borrows/{borrow}/reject', [BorrowController::class, 'reject'])->name('borrows.reject');
    });

    // Profile routes accessible by all authenticated users
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
