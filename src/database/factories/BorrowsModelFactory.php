<?php

namespace Database\Factories;

use App\Models\BorrowsModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BorrowsModelFactory extends Factory
{
    protected $model = BorrowsModel::class;

    public function definition(): array
    {
        return [
            'member_id' => User::factory(),
            'borrowed_at' => now(),
        ];
    }
}

