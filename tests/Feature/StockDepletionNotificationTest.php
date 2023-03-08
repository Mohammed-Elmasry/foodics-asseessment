<?php

namespace Tests\Feature;

use App\Mail\StockDepletionEmail;
use App\Models\Ingredient;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class StockDepletionNotificationTest extends TestCase
{
    use RefreshDatabase;

    private string $orderCreationUrl;
    private Product $burger;

    public function testNoEmailIsSentWhileIngredientsAmountsAreAboveThreshold()
    {
        Mail::fake();

        $this->postJson($this->orderCreationUrl, $this->requestData());

        Mail::assertNothingSent();
    }

    public function testDepletionNotificationEmailIsSentWhenIngredientsAvailableAmountIsBelowThreshold()
    {
        Mail::fake();

        Ingredient::all()->each(function ($ingredient) {
            $ingredient->available_amount_in_grams = $ingredient->notificationThreshold();
            $ingredient->save();
        });

        $this->postJson($this->orderCreationUrl, $this->requestData());

        Mail::assertSent(StockDepletionEmail::class);
    }

    public function testDepletionEmailIsSentOnlyOnceForAGivenIngredient()
    {
        Mail::shouldReceive('send')->once();

        $beef = Ingredient::where(["name" => "beef"])->first();
        $beef->available_amount_in_grams = 0;
        $beef->save();

        $this->postJson($this->orderCreationUrl, $this->requestData());
        $this->postJson($this->orderCreationUrl, $this->requestData());
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
    }
}
