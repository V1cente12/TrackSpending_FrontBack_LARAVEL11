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
            ['name' => 'Salary', 'type' => 'income'],
            ['name' => 'Debt Collection', 'type' => 'income'],
            ['name' => 'Freelance Work', 'type' => 'income'],
            ['name' => 'Investments', 'type' => 'income'],
            ['name' => 'Rental Income', 'type' => 'income'],
            ['name' => 'Bonus', 'type' => 'income'],

            // Expense Categories
            ['name' => 'Food & Groceries', 'type' => 'expense'],
            ['name' => 'Transportation', 'type' => 'expense'],
            ['name' => 'Entertainment', 'type' => 'expense'],
            ['name' => 'Electronics', 'type' => 'expense'],
            ['name' => 'Utilities', 'type' => 'expense'],
            ['name' => 'Rent/Housing', 'type' => 'expense'],
            ['name' => 'Healthcare', 'type' => 'expense'],
            ['name' => 'Clothing', 'type' => 'expense'],
            ['name' => 'Education', 'type' => 'expense'],
            ['name' => 'Loans Given', 'type' => 'expense'],
            ['name' => 'Restaurants', 'type' => 'expense'],
            ['name' => 'Gaming', 'type' => 'expense'],
            ['name' => 'Internet & Phone', 'type' => 'expense'],
            ['name' => 'Travel', 'type' => 'expense'],
        ];

        DB::table('categories')->insert($categories);
    }
}