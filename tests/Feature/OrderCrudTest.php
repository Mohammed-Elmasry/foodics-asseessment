<?php

namespace Tests\Feature;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private string $orderCreationUrl;
    /**
     * @var Collection|\Illuminate\Database\Eloquent\Factories\Model|Model
     */
    private $product;

    public function testCreatingOrdersReturnsAJson()
    {
        $response = $this->sendRequest();

        $response->assertHeader("Content-Type", "application/json");
    }

    public function testCreatingOrdersReturns201Created()
    {
        $response = $this->sendRequest();

        $response->assertCreated();
        $response->assertJson(["message" => "Order Created"]);
    }

    public function testCreatedOrderAddsRecordsToDatabase()
    {
        $this->sendRequest();

        $this->assertDatabaseCount("orders", 1);
    }

    private function requestData()
    {
        return [
            "products" => [
                [
                    "product_id" => $this->product->id,
                    "quantity" => 3
                ]
            ]
        ];
    }

    /**
     * @return \Illuminate\Testing\TestResponse
     */
    public function sendRequest(): \Illuminate\Testing\TestResponse
    {
        return $this->post($this->orderCreationUrl, $this->requestData());
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderCreationUrl = "/api/orders";
        $this->product = Product::factory()->create(["product_name" => "Burger"]);

    }
}
