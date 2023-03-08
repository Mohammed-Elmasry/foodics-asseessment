<?php


namespace App\Services;


use App\Events\IngredientUpdatedEvent;
use App\Mail\StockDepletionEmail;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class IngredientsService
{
    const NOTIFICATION_THRESHOLD_RATIO = 0.5;

    public function updateIngredientsAmounts(Product $product, int $quantity = 1)
    {
        DB::transaction(function () use ($product, $quantity) {
            foreach ($product->ingredients as $ingredient) {
                $this->updateIngredientAvailableAmount($ingredient, $quantity);

                event(new IngredientUpdatedEvent($ingredient));
            }
        });
    }

    /**
     * @param $ingredient
     * @param int $quantity
     */
    public function updateIngredientAvailableAmount($ingredient, int $quantity): void
    {
        $ingredient->available_amount_in_grams -= ($ingredient->pivot->used_amount * $quantity);
        $ingredient->save();
    }
}
