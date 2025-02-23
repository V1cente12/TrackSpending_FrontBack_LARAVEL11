<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CurrencySeeder extends Seeder
{
    public function run()
    {
        $currencies = [
            ['name' => 'Boliviano', 'code' => 'BOB', 'symbol' => 'Bs'],
            ['name' => 'US Dollar', 'code' => 'USD', 'symbol' => '$'],
            ['name' => 'Euro', 'code' => 'EUR', 'symbol' => '€'],
            ['name' => 'British Pound', 'code' => 'GBP', 'symbol' => '£'],
            ['name' => 'Japanese Yen', 'code' => 'JPY', 'symbol' => '¥'],
            ['name' => 'Mexican Peso', 'code' => 'MXN', 'symbol' => 'MX$'],
            ['name' => 'Brazilian Real', 'code' => 'BRL', 'symbol' => 'R$'],
            ['name' => 'Argentine Peso', 'code' => 'ARS', 'symbol' => '$'],
            ['name' => 'Canadian Dollar', 'code' => 'CAD', 'symbol' => 'C$'],
        ];

        DB::table('currencies')->insert($currencies);
    }
}
