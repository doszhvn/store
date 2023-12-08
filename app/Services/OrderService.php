<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function store($data, $products)
    {
        $insufficientStock = false;
        $updatedProducts = [];

        foreach ($products as $product) {
            $productId = $product['product_id'];
            $quantity = $product['quantity'];


            $productModel = Product::find($productId);

            if ($productModel) {
                // Проверяем, достаточно ли товаров на складе
                if ($productModel->quantity < $quantity) {
                    $insufficientStock = true;
                    break; // Прерываем цикл, если товаров недостаточно
                } else {
                    // Если товаров достаточно, добавляем модель в массив для дальнейшего сохранения
                    $productModel->quantity -= $quantity;
                    $updatedProducts[] = $productModel;
                }
            } else {
                return response()->json(["error" => 'Product not found'], 404);
            }
        }

        if ($insufficientStock) {
            return response()->json(["error" => 'There are not enough products in stock'], 422);
        }

        // Сохраняем все измененные модели после успешной проверки всех продуктов
        foreach ($updatedProducts as $updatedProduct) {
            $updatedProduct->save();
        }

        // Создаем заказ
        $order = Order::create($data);

        if ($order) {
            $productsWithOrderId = array_map(function ($product) use ($order) {
                $product['order_id'] = $order->id;
                return $product;
            }, $products);

            // Записываем продукты в заказ
            OrderProduct::insert($productsWithOrderId);

            return response()->json(["message" => 'Order created successfully']);
        } else {
            return response()->json(["error" => 'Order creation failed'],500);
        }
    }


    public function cancelOrder($orderId)
    {
        // Находим заказ в базе данных
        $order = Order::find($orderId);

        if (!$order) {
            return response()->json(["error" => 'Order not found'], 404);
        }

        try {
            // Начинаем транзакцию
            DB::beginTransaction();

            // Возвращаем количество товаров на склад для каждого продукта в заказе
            foreach ($order->orderProducts as $orderProduct) {
                $productId = $orderProduct->product_id;
                $quantity = $orderProduct->quantity; // количество товара в заказе

                // Находим продукт в базе данных
                $productModel = Product::find($productId);

                if ($productModel) {
                    $productModel->quantity += $quantity;
                    $productModel->save();
                } else {
                    // Если продукт не найден, отменяем транзакцию и возвращаем ошибку
                    DB::rollBack();
                    return response()->json(["error" => 'Product not found'], 404);
                }
            }

            // Удаляем связанные записи в таблице order_products
            $order->orderProducts()->delete();

            // Удаляем сам заказ
            $order->delete();

            // Фиксируем изменения в базе данных
            DB::commit();

            return response()->json(["message" => 'Order canceled successfully']);
        } catch (\Exception $e) {
            // Если произошла ошибка, отменяем транзакцию и возвращаем ошибку
            DB::rollBack();
            return response()->json(["error" => 'Failed to cancel order'], 500);
        }
    }
}
