<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentMethodSeeder extends Seeder
{
    public function run()
    {
        $methods = [
            ['name' => 'QR', 'icon' => '📱', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Cash', 'icon' => '💵', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Card', 'icon' => '💳', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Online Payment', 'icon' => '🌐', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Bank Transfer', 'icon' => '🏦', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('payment_methods')->insert($methods);
    }
}