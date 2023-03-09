<?php


namespace App\Services;


use App\Models\Order;

class OrdersService
{
    /**
     * @param Order $order
     * @param array $products
     */
    public function makeOrder(array $products): void
    {
        $order = Order::create();

        foreach ($products as $product)
            $order->products()->attach($product["product_id"], ["quantity" => $product["quantity"]]);
    }
}
