<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            // Income Categories
            ['name' => 'Salary', 'type' => 'income', 'icon' => '💰', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Debt Collection', 'type' => 'income', 'icon' => '💵', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Freelance Work', 'type' => 'income', 'icon' => '💻', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Investments', 'type' => 'income', 'icon' => '📈', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rental Income', 'type' => 'income', 'icon' => '🏠', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bonus', 'type' => 'income', 'icon' => '🎁', 'created_at' => now(), 'updated_at' => now()],

            // Expense Categories
            ['name' => 'Food & Groceries', 'type' => 'expense', 'icon' => '🛒', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Transportation', 'type' => 'expense', 'icon' => '🚗', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Entertainment', 'type' => 'expense', 'icon' => '🎬', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Electronics', 'type' => 'expense', 'icon' => '📱', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Utilities', 'type' => 'expense', 'icon' => '💡', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Rent/Housing', 'type' => 'expense', 'icon' => '🏢', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Healthcare', 'type' => 'expense', 'icon' => '🏥', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Clothing', 'type' => 'expense', 'icon' => '👕', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Education', 'type' => 'expense', 'icon' => '📚', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Loans Given', 'type' => 'expense', 'icon' => '🏦', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Restaurants', 'type' => 'expense', 'icon' => '🍽️', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gaming', 'type' => 'expense', 'icon' => '🎮', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Internet & Phone', 'type' => 'expense', 'icon' => '📶', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Travel', 'type' => 'expense', 'icon' => '✈️', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}