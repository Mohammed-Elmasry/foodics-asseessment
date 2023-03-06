<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreationRequest;
use App\Models\Order;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{

    public function store(OrderCreationRequest $request)
    {
        try {
            $order = Order::create();

            $products = $request->input("products");

            foreach ($products as $product) {
                $order->products()->attach($product["product_id"], ["quantity" => $product["quantity"]]);
            }

        } catch (\RuntimeException $exception) {
            abort(422);
        }
        return response()->json(["message" => "Order Created"], Response::HTTP_CREATED);
    }
}
