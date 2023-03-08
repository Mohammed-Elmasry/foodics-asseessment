<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreationRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\IngredientsService;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{

    public function store(OrderCreationRequest $request)
    {
        $order = Order::create();

        $products = $request->input("products");
        $service = new IngredientsService();

        foreach ($products as $product) {
            $order->products()->attach($product["product_id"], ["quantity" => $product["quantity"]]);
            $service->updateIngredientsAmounts(Product::find($product["product_id"]), $product["quantity"]);
        }

        return response()->json(["message" => "Order Created"], Response::HTTP_CREATED);
    }
}
