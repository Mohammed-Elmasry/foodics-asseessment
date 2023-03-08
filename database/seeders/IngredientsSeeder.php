<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IngredientsSeeder extends Seeder
{
    const kilo = 1000;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Ingredient::firstOrCreate([
            "name" => "beef",
            "available_amount_in_grams" => 20 * self::kilo,
            "original_total_amount" => 20 * self::kilo,

        ]);

        Ingredient::firstOrCreate([
            "name" => "Cheese",
            "available_amount_in_grams" => 5 * self::kilo,
            "original_total_amount" => 5 * self::kilo
        ]);

        Ingredient::firstOrCreate([
            "name" => "Onion",
            "available_amount_in_grams" => 1 * self::kilo,
            "original_total_amount" => 1 * self::kilo
        ]);
    }
}
