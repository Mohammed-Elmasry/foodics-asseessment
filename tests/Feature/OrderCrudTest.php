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

    private Product $product;

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
        $quantity = rand(1, 10);
        $this->post($this->orderCreationUrl, $this->requestData([
            "products" => [
                [
                    "product_id" => $this->product->id,
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

    private function requestData(?array $data = [])
    {
        $default = [
            "products" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => 3
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
        $this->product = Product::where(["product_name" => "Burger"])->first();

    }
}
