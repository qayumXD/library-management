<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Author;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create categories
        $categories = [
            ['name' => 'Fiction', 'description' => 'Works of fiction including novels and short stories'],
            ['name' => 'Non-Fiction', 'description' => 'Factual works including biographies and history'],
            ['name' => 'Science', 'description' => 'Scientific works and research'],
            ['name' => 'Technology', 'description' => 'Books about computers and technology'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        // Create authors
        $authors = [
            ['name' => 'J.K. Rowling', 'biography' => 'British author best known for the Harry Potter series'],
            ['name' => 'George R.R. Martin', 'biography' => 'American novelist and short story writer'],
            ['name' => 'Stephen King', 'biography' => 'American author of horror and supernatural fiction'],
            ['name' => 'Yuval Noah Harari', 'biography' => 'Israeli historian and professor'],
        ];

        foreach ($authors as $author) {
            Author::create($author);
        }

        // Create books
        $books = [
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'isbn' => '9780747532743',
                'description' => 'The first book in the Harry Potter series',
                'category_id' => 1,
                'author_id' => 1,
                'quantity' => 5,
            ],
            [
                'title' => 'A Game of Thrones',
                'isbn' => '9780553103540',
                'description' => 'The first book in A Song of Ice and Fire series',
                'category_id' => 1,
                'author_id' => 2,
                'quantity' => 3,
            ],
            [
                'title' => 'The Shining',
                'isbn' => '9780385121675',
                'description' => 'A horror novel by Stephen King',
                'category_id' => 1,
                'author_id' => 3,
                'quantity' => 4,
            ],
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'isbn' => '9780062316097',
                'description' => 'A book about the history of human evolution',
                'category_id' => 2,
                'author_id' => 4,
                'quantity' => 2,
            ],
        ];

        foreach ($books as $book) {
            Book::create($book);
        }

        // Create a test user if not exists
        $user = User::firstOrCreate(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        // Create some borrows
        $borrows = [
            [
                'book_id' => 1,
                'user_id' => $user->id,
                'borrowed_at' => Carbon::now()->subDays(5),
                'due_date' => Carbon::now()->addDays(9),
                'returned_at' => null,
            ],
            [
                'book_id' => 2,
                'user_id' => $user->id,
                'borrowed_at' => Carbon::now()->subDays(10),
                'due_date' => Carbon::now()->subDays(3),
                'returned_at' => Carbon::now()->subDays(4),
            ],
        ];

        foreach ($borrows as $borrow) {
            Borrow::create($borrow);
        }
    }
}
