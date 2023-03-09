<?php


namespace App\Services;


use App\Events\IngredientUpdatedEvent;
use App\Models\Product;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

class IngredientsService
{
    const NOTIFICATION_THRESHOLD_RATIO = 0.5;

    public function updateIngredientsAmounts(Product $product, int $quantity = 1)
    {
        DB::transaction(function () use ($product, $quantity) {
            foreach ($product->ingredients as $ingredient) {
                $this->updateIngredientAvailableAmount($product->product_name, $ingredient, $quantity);

                event(new IngredientUpdatedEvent($ingredient));
            }
        });
    }

    /**
     * @param string $productName
     * @param $ingredient
     * @param int $quantity
     */
    public function updateIngredientAvailableAmount(string $productName, $ingredient, int $quantity): void
    {
        if (!Redis::exists($productName . ":" . $ingredient->name))
            Redis::set($productName . ":" . $ingredient->name, $ingredient->pivot->used_amount);

        $ingredient->available_amount_in_grams -= (Redis::get($productName . ":" . $ingredient->name) * $quantity);
        $ingredient->save();
    }

}
