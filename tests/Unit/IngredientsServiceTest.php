<?php

namespace Tests\Unit;

use App\Models\Ingredient;
use App\Models\Product;
use App\Services\IngredientsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IngredientsServiceTest extends TestCase
{
    use RefreshDatabase;

    const KILO = 1000;

    public function testIngredientsServiceUpdatesAvailableIngredientsAmountsBasedOnOrderedProduct(): void
    {
        $this->seed();

        list($beef, $cheese, $onion, $product) = $this->retrieveModelsFromDB();

        $service = new IngredientsService();

        $service->updateIngredientsAmounts($product);

        $this->refreshModels($beef, $cheese, $onion);

        $this->assertEquals($beef->available_amount_in_grams, (20 * self::KILO - 150));
        $this->assertEquals($cheese->available_amount_in_grams, (5 * self::KILO - 30));
        $this->assertEquals($onion->available_amount_in_grams, (1 * self::KILO - 20));
    }

    public function testAvailableAmountsAreDeductedBasedOnQuantityOfOrderedProducts()
    {
        $this->seed();

        list($beef, $cheese, $onion, $product) = $this->retrieveModelsFromDB();

        $service = new IngredientsService();

        $service->updateIngredientsAmounts($product, 2);

        // update the latest from db
        $this->refreshModels($beef, $cheese, $onion);

        $this->assertEquals($beef->available_amount_in_grams, (20 * self::KILO - (150 * 2)));
        $this->assertEquals($cheese->available_amount_in_grams, (5 * self::KILO - (30 * 2)));
        $this->assertEquals($onion->available_amount_in_grams, (1 * self::KILO - (20 * 2)));
    }

    /**
     * @param $beef
     * @param $cheese
     * @param $onion
     */
    public function refreshModels($beef, $cheese, $onion): void
    {
        $beef->refresh();
        $cheese->refresh();
        $onion->refresh();
    }

    /**
     * @return array
     */
    public function retrieveModelsFromDB(): array
    {
        $beef = Ingredient::where(["name" => "beef"])->first();
        $cheese = Ingredient::where(["name" => "cheese"])->first();
        $onion = Ingredient::where(["name" => "onion"])->first();

        $product = Product::where(["product_name" => "Burger"])->first();
        return array($beef, $cheese, $onion, $product);
    }

}
