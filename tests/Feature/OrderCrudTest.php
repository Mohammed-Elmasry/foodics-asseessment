<?php

namespace Tests\Feature;

use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse;
use Tests\TestCase;
use function PHPUnit\Framework\assertEquals;

class OrderCrudTest extends TestCase
{
    use RefreshDatabase;

    const KILO = 1000;

    private string $orderCreationUrl;

    private Product $burger;
    private Product $pizza;

    public function testCreatingOrdersReturnsAJson()
    {
        $response = $this->post($this->orderCreationUrl, $this->requestData());

        $response->assertHeader("Content-Type", "application/json");
    }

    public function testCreatingOrdersReturns201Created()
    {
        $response = $this->post($this->orderCreationUrl, $this->requestData());

        $response->assertCreated();
        $response->assertJson(["message" => "Order Created"]);
    }

    public function testCreatedOrderAddsRecordsToDatabase()
    {
        $this->post($this->orderCreationUrl, $this->requestData());

        $this->assertDatabaseCount("orders", 1);
    }

    public function testMakingANewOrderReducesAmountOfAvailableIngredientsOfOrderedProduct()
    {
        $quantity = 2;
        $this->post($this->orderCreationUrl, $this->requestData([
            "products" => [
                [
                    "product_id" => $this->burger->id,
                    "quantity" => $quantity
                ]
            ]
        ]));

        $beef = Ingredient::where(["name" => "beef"])->first();
        $cheese = Ingredient::where(["name" => "cheese"])->first();
        $onion = Ingredient::where(["name" => "onion"])->first();

        $this->assertEquals($beef->available_amount_in_grams, (20 * self::KILO - (150 * $quantity)));
        $this->assertEquals($cheese->available_amount_in_grams, (5 * self::KILO - (30 * $quantity)));
        $this->assertEquals($onion->available_amount_in_grams, (1 * self::KILO - (20 * $quantity)));
    }

    public function testMakingOrderWithMultipleProductsDeductsIngredientsAmountsForEachProductPerQuantity()
    {
        $quantity1 = 1;
        $quantity2 = 1;
        $this->post($this->orderCreationUrl, $this->requestData([
            "products" => [
                [
                    "product_id" => $this->burger->id,
                    "quantity" => $quantity1
                ], [
                    "product_id" => $this->pizza->id,
                    "quantity" => $quantity2
                ]
            ]
        ]));

        $beef = Ingredient::where(["name" => "beef"])->first();
        $cheese = Ingredient::where(["name" => "cheese"])->first();
        $onion = Ingredient::where(["name" => "onion"])->first();

        $this->assertEquals($beef->available_amount_in_grams, 20000 - ((150 * $quantity1) + (500 * $quantity2)));
        $this->assertEquals($cheese->available_amount_in_grams, 5000 - ((30 * $quantity1) + (400 * $quantity2)));
        $this->assertEquals($onion->available_amount_in_grams, 1000 - ((20 * $quantity1) + (300 * $quantity2)));
    }

    private function requestData(?array $data = [])
    {
        $default = [
            "products" => [
                [
                    "product_id" => $this->burger->id,
                    "quantity" => 1
                ]
            ]
        ];
        return array_merge($default, $data);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
        $this->orderCreationUrl = "/api/orders";
        $this->burger = Product::where(["product_name" => "Burger"])->first();
        $this->pizza = Product::where(["product_name" => "Pizza"])->first();
    }
}
