<?php
namespace App\Services;

use App\Models\Order;

class OrderService {
    public function store($data)
    {
        if (Order::create($data)) {
            return ["message" => 'data created successful'];
        } else {
            return ["error" => 'data creating failed'];
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
