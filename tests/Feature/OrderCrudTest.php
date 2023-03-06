<?php

namespace Tests\Feature;

use Carbon\Traits\Date;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use function PHPUnit\Framework\assertTrue;

class OrderCrudTest extends TestCase
{
    /**
     * @var string
     */
    private string $orderCreationUrl;

    public function testCreatingOrdersReturnsAJson()
    {
        $this->withoutExceptionHandling();

        $response = $this->post($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 3
                ]
            ]
        ]);

        $response->assertJson(["message" => "Order Created"]);
        $response->assertHeader("Content-Type", "application/json");
    }


    protected function setUp(): void
    {
        parent::setUp();
        $this->orderCreationUrl = "/api/orders";
    }
}
