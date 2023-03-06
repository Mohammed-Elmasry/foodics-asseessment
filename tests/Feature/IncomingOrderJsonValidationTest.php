<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class IncomingOrderJsonValidationTest extends TestCase
{

    private string $orderCreationUrl = "/api/orders";

    public function testReturn422IfProductsIsNotAnArray()
    {
        $response = $this->postJson($this->orderCreationUrl, ["products" => ""]);
        $response->assertUnprocessable();
    }

    public function testReturn422IfBodyHasMalformedMainProductsKeyName()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "orders" => [
                [
                    "product_id" => 1,
                    "quantity" => 12
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }

    public function testReturn422IfProductsArrayHasMalformedProductIdKeyName()
    {
        $response = $this->postJson($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 154
                ], [
                    "product" => 2,
                    "quantity" => 2
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }

    public function testReturn422IfProductHasIfProductsArrayHasMalformedQuantityKeyName()
    {

        $response = $this->postJson($this->orderCreationUrl, [
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 154
                ], [
                    "product_id" => 2,
                    "number" => 34
                ]
            ]
        ]);
        $response->assertUnprocessable();
    }
}
