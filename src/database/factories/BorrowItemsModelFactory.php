<?php

namespace Database\Factories;

use App\Models\BorrowItemsModel;
use App\Models\BorrowsModel;
use App\Models\BooksModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowItemsModelFactory extends Factory
{
    protected $model = BorrowItemsModel::class;

    public function definition(): array
    {
        return [
            'borrow_id' => BorrowsModel::factory(),
            'book_id' => BooksModel::factory(),
            'quantity' => fake()->numberBetween(1, 5),
        ];
    }
}

