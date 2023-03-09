<?php


namespace App\Services;


use App\Models\Order;

class OrdersService
{
    /**
     * @param Order $order
     * @param array $products
     */
    public function makeOrder(Order $order, array $products): void
    {
        foreach ($products as $product)
            $order->products()->attach($product["product_id"], ["quantity" => $product["quantity"]]);
    }
}
