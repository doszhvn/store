<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;

class OrderService
{
    public function store($data, $products)
    {
        $order = Order::create($data);

        // Если заказ успешно создан, продолжаем
        if ($order) {
            // Добавляем ID заказа к каждому продукту
            $productsWithOrderId = array_map(function ($product) use ($order) {
                $product['order_id'] = $order->id;
                return $product;
            }, $products);

            // Создаем связанные продукты
            OrderProduct::insert($productsWithOrderId);

            return ["message" => 'Order created successfully'];
        } else {
            return ["error" => 'Order creation failed'];
        }
    }

    public function update($category, $data)
    {
        if ($category->update($data)) {
            return ["message" => 'data updated successful'];
        } else {
            return ["error" => 'data updating failed'];
        }
    }
}
