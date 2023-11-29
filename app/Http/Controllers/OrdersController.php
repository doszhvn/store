<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Services\OrderService;

class OrdersController extends Controller
{
    public function index()
    {

        $orders = Order::leftJoin('users', 'orders.user_id', '=', 'users.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
//            ->where('user_id', '=', auth()->user()->id)
            ->select(
                'orders.id',
                'orders.dateBuy',
                'users.id AS client_id',
                'users.last_name_doc',
                'users.phone_number',
                'users.email',
                'products.id AS product_id',
                'products.name AS product_name',
                'products.price AS product_price',
                'categories.id AS category_id',
                'categories.name AS category_name'
            )->get();

        $formattedOrders = [];

        foreach ($orders as $order) {
            $formattedOrder = [
                'order_id' => $order->id,
                'order_date' => $order->dateBuy,
                'client' => [
                    'client_id' => $order->client_id,
                    'client_name' => $order->last_name_doc,
                    'client_email' => $order->email,
                ],
                'products' => [
                    'product_id' => $order->product_id,
                    'product_name' => $order->product_name,
                    'product_price' => $order->product_price,
                    'category' => [
                        'category_id' => $order->category_id,
                        'category_name' => $order->category_name,
                    ],
                ],
            ];

            // Проверка, существует ли заказ в $formattedOrders
            $existingOrder = collect($formattedOrders)->firstWhere('order_id', $order->order_id);

            // Если заказ уже существует, добавляем продукт к существующему заказу
            if ($existingOrder) {
                $existingOrder['products'][] = $formattedOrder['products'][0];
            } else {
                $formattedOrders[] = $formattedOrder;
            }
        }

        return response()->json(['orders' => $formattedOrders]);
    }




    /**
     * @return Order
     */

    public function dataById(Order $dataId)
    {
        $order = Order::leftJoin('clients', 'orders.client_id', '=', 'clients.id')
            ->leftJoin('products', 'orders.product_id', '=', 'products.id')
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->where('orders.id', '=', $dataId['id'])
            ->select(
                'orders.id',
                'orders.dateBuy',
                'clients.id AS client_id',
                'clients.last_name_doc',
                'clients.phone_number',
                'products.id AS product_id',
                'products.name AS product_name',
                'products.price AS product_price',
                'categories.id AS category_id',
                'categories.name AS category_name'
            )
            ->first();

        if ($order) {
            $formattedOrder = [
                'order_id' => $order->id,
                'order_date' => $order->dateBuy,
                'client' => [
                    'client_id' => $order->client_id,
                    'client_name' => $order->last_name_doc,
                    'client_email' => $order->phone_number,
                ],
                'products' => [
                    'product_id' => $order->product_id,
                    'product_name' => $order->product_name,
                    'product_price' => $order->product_price,
                    'category' => [
                        'category_id' => $order->category_id,
                        'category_name' => $order->category_name,
                    ],
                ],
            ];

            return response()->json($formattedOrder);
        } else {
            return response()->json(['error' => 'Order not found'], 404);
        }

    }

    /**
     * @param OrderRequest $request
     * @param OrderService $service
     * @param Order $dataId
     * @return string[]
     */
    public function update(OrderRequest $request, OrderService $service, Order $dataId)
    {

        $updatedOrder = $request->validated();
        if ($updatedOrder) {
            return $service->update($dataId, $updatedOrder);
        }
    }

    /**
     * @param OrderRequest $request
     * @return string
     */
    public function store(OrderRequest $request, OrderService $service)
    {
        $data = $request->validated();
        $data['user_id'] = auth()->user()->id;
        if ($data) {
            return $service->store($data);
        }
    }

    /**
     * @param $id
     * @return string
     */
    public function delete($dataId)
    {
        $category = Order::find($dataId);
        if ($category->delete()) {
            return 'successfully deleted';
        } else {
            return 'not deleted';
        }
    }
}
