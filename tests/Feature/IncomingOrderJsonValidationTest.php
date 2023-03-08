<?php

namespace Tests\Feature;

use App\Models\Product;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncomingOrderJsonValidationTest extends TestCase
{
    use RefreshDatabase;

    private string $orderCreationUrl = "/api/orders";
    /**
     * @var \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Factories\TModel|\Illuminate\Database\Eloquent\Model
     */
    private $product;

    public function testReturn422IfProductsIsNotAnArray()
    {
        $response = $this->postJson($this->orderCreationUrl, ["products" => ""]);
        $response->assertUnprocessable();
    }

    public function testThrowUnprocessableContentIfBodyHasMalformedMainProductsKeyName()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "orders" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => 12
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }

    public function testThrowUnprocessableContentIfProductsArrayHasMalformedProductIdKeyName()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => 154
                ], [
                    "product" => 2,
                    "quantity" => 2
                ]
            ]
        ]);

        $response->assertUnprocessable();
    }

    public function testThrowUnprocessableContentIfProductHasIfProductsArrayHasMalformedQuantityKeyName()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => 154
                ], [
                    "product_id" => 2,
                    "number" => 34
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }

    public function testThrowUnprocessableContentIfProductHasIfQuantityLessThan1()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => -1
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }

    public function testThrowUnprocessableContentIfNonExistentProductIsReferenced()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => 10,
                    "quantity" => 2
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create(["product_name" => "Burger"]);
    }
}
