<?php

namespace Tests\Feature;

use Carbon\Traits\Date;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertTrue;

class OrderCrudTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @var string
     */
    private string $orderCreationUrl;

    public function testCreatingOrdersReturnsAJson()
    {
        $this->withoutExceptionHandling();

        $response = $this->post($this->orderCreationUrl, $this->requestData());

        $response->assertHeader("Content-Type", "application/json");
    }

    public function testCreatingOrdersReturns201Created()
    {
        $this->withoutExceptionHandling();

        $response = $this->post($this->orderCreationUrl, $this->requestData());

        $response->assertCreated();
    }

    public function testCreatingOrdersReturnsMessageCreated()
    {
        $this->withoutExceptionHandling();

        $response = $this->post($this->orderCreationUrl, $this->requestData());

        $response->assertJson(["message" => "Order Created"]);
    }

    public function testCreatedOrderAppearsAsCountInDatabase()
    {
        $response = $this->postJson($this->orderCreationUrl, $this->requestData());

        $this->assertDatabaseCount("orders", 1);
    }

    private function requestData()
    {
        return [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 3
                ]
            ]
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->orderCreationUrl = "/api/orders";
    }
}
