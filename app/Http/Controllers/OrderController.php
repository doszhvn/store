<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Services\OrderService;


class OrderController extends Controller
{
    public function index()
    {

        $orders = Order::with(['user', 'products.category'])
            ->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'order_date' => $order->dateBuy,
                'client' => [
                    'client_id' => $order->user->id,
                    'client_name' => $order->user->last_name_doc,
                    'client_email' => $order->user->email,
                ],
                'products' => $order->products->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $product->price,
                        'quantity' => $product->quantity,
                        'category' => [
                            'category_id' => $product->category->id,
                            'category_name' => $product->category->name,
                        ],
                    ];
                }),
            ];
        });

        return response()->json($formattedOrders);
    }
    public function userOrders()
    {

        $orders = Order::with(['user', 'products.category'])
            ->where('user_id', '=', auth()->user()->id)
            ->get();

        $formattedOrders = $orders->map(function ($order) {
            return [
                'order_id' => $order->id,
                'order_date' => $order->dateBuy,
                'client' => [
                    'client_id' => $order->user->id,
                    'client_name' => $order->user->last_name_doc,
                    'client_email' => $order->user->email,
                ],
                'products' => $order->products->map(function ($product) use ($order) {
                    $orderProduct = $order->orderProducts->where('product_id', $product->id)->first();
                    return [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $product->price,
                        'category' => [
                            'category_id' => $product->category->id,
                            'category_name' => $product->category->name,
                        ],
                        'quantity' => $product->quantity,
                        'ordered_quantity' => $orderProduct ? $orderProduct->quantity : 0,
                    ];

                }),
            ];
        });

        return response()->json($formattedOrders);
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */

    public function show(Order $dataId)
    {
        $order = Order::with(['user', 'products.category'])
            ->where('orders.id', '=', $dataId['id'])
            ->first();

        if ($order) {
            $formattedOrder = [
                'order_id' => $order->id,
                'order_date' => $order->dateBuy,
                'client' => [
                    'client_id' => $order->user->id,
                    'client_name' => $order->user->last_name_doc,
                    'client_email' => $order->user->email,
                ],
                'products' => $order->products->map(function ($product) {
                    return [
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_price' => $product->price,
                        'category' => [
                            'category_id' => $product->category->id,
                            'category_name' => $product->category->name,
                        ],
                    ];
                }),
            ];

            return response()->json($formattedOrder);
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }

    }

    /**
     * @param OrderRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(OrderRequest $request, OrderService $service)
    {
        $products = $request->validated();
        $order['user_id'] = auth()->user()->id;
        if ($order) {
            return $service->store($order, $products);
        }
    }


    public function cancelOrder(OrderService $service, $orderId)
    {
        return $service->cancelOrder($orderId);
    }
}
