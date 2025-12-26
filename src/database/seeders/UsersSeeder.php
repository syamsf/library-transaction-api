<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Library Admin',
                'email' => 'admin@library.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'John Smith',
                'email' => 'john.smith@library.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Alice Johnson',
                'email' => 'alice.johnson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Bob Williams',
                'email' => 'bob.williams@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Carol Davis',
                'email' => 'carol.davis@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'David Martinez',
                'email' => 'david.martinez@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Emma Garcia',
                'email' => 'emma.garcia@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Frank Rodriguez',
                'email' => 'frank.rodriguez@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Grace Lee',
                'email' => 'grace.lee@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Henry Wilson',
                'email' => 'henry.wilson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Ivy Anderson',
                'email' => 'ivy.anderson@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Jack Taylor',
                'email' => 'jack.taylor@example.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ],
        ];

        User::upsert(
            $users,
            ['email'],
            ['name', 'password', 'email_verified_at']
        );
    }
}

