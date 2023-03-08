<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::firstOrCreate([
            "product_name" => "Burger"
        ]);

        $beef = Ingredient::where(["name" => "beef"])->first();
        $cheese = Ingredient::where(["name" => "cheese"])->first();
        $onion = Ingredient::where(["name" => "onion"])->first();

        $product->ingredients()->sync([
            $beef->id => ["used_amount" => 150],
            $cheese->id => ["used_amount" => 30],
            $onion->id => ["used_amount" => 20]
        ]);

        $product = Product::create([
            "product_name" => "Pizza"
        ]);

        $product->ingredients()->sync([
            $beef->id => ["used_amount" => 500],
            $cheese->id => ["used_amount" => 400],
            $onion->id => ["used_amount" => 300]
        ]);
    }
}
