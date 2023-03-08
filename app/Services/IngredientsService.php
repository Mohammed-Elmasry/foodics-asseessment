<?php


namespace App\Services;


use App\Events\IngredientUpdatedEvent;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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
