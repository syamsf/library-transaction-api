<?php

namespace Database\Seeders;

use App\Models\BooksModel;
use Illuminate\Database\Seeder;

class BooksSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'stock' => 10,
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'stock' => 8,
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'stock' => 12,
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'stock' => 15,
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'stock' => 7,
            ],
            [
                'title' => 'Harry Potter and the Philosopher\'s Stone',
                'author' => 'J.K. Rowling',
                'stock' => 20,
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'stock' => 9,
            ],
            [
                'title' => 'The Lord of the Rings',
                'author' => 'J.R.R. Tolkien',
                'stock' => 11,
            ],
            [
                'title' => 'Animal Farm',
                'author' => 'George Orwell',
                'stock' => 14,
            ],
            [
                'title' => 'Brave New World',
                'author' => 'Aldous Huxley',
                'stock' => 6,
            ],
            [
                'title' => 'The Chronicles of Narnia',
                'author' => 'C.S. Lewis',
                'stock' => 13,
            ],
            [
                'title' => 'Jane Eyre',
                'author' => 'Charlotte Brontë',
                'stock' => 5,
            ],
            [
                'title' => 'Wuthering Heights',
                'author' => 'Emily Brontë',
                'stock' => 8,
            ],
            [
                'title' => 'Moby-Dick',
                'author' => 'Herman Melville',
                'stock' => 4,
            ],
            [
                'title' => 'The Odyssey',
                'author' => 'Homer',
                'stock' => 10,
            ],
        ];

        foreach ($books as $book) {
            BooksModel::create($book);
        }
    }
}

