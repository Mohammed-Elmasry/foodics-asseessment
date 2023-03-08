<?php


namespace App\Services;


use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class IngredientsService
{

    public function updateIngredientsAmounts(Product $product, int $quantity = 1)
    {
        DB::transaction(function () use ($product, $quantity) {
            foreach ($product->ingredients as $ingredient) {
                $ingredient->available_amount_in_grams -= ($ingredient->pivot->used_amount * $quantity);
                $ingredient->save();
            }
        });
    }
}
