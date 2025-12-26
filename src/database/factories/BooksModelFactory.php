<?php

namespace Database\Factories;

use App\Models\BooksModel;
use Illuminate\Database\Eloquent\Factories\Factory;

class BooksModelFactory extends Factory
{
    protected $model = BooksModel::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'stock' => fake()->numberBetween(0, 50),
        ];
    }

    public function withStock(int $stock): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $stock,
        ]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => 0,
        ]);
    }
}

