<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderCreationRequest;
use App\Models\Order;
use App\Models\Product;
use App\Services\IngredientsService;
use App\Services\OrdersService;
use Symfony\Component\HttpFoundation\Response;

class OrderController extends Controller
{

    public function store(OrderCreationRequest $request, IngredientsService $ingredientsService, OrdersService $ordersService)
    {
        $order = Order::create();

        $products = $request->input("products");

        $ordersService->makeOrder($order, $products);

        foreach ($products as $product) {
            $ingredientsService->updateIngredientsAmounts(Product::find($product["product_id"]), $product["quantity"]);
        }

        return response()->json(["message" => "Order Created"], Response::HTTP_CREATED);
    }


}
