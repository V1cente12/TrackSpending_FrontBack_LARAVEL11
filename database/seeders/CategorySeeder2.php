<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder2 extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $categories = [
            // Income Category
            ['name' => 'Other Income', 'type' => 'income', 'icon' => '💎', 'created_at' => now(), 'updated_at' => now()],
            
            // Expense Category
            ['name' => 'Other Expenses', 'type' => 'expense', 'icon' => '🔮', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('categories')->insert($categories);
    }
}
