<?php

namespace App\Repositories;

use App\Models\Order;
use App\Interfaces\OrderInterface;

class OrderRepository implements OrderInterface
{
    protected $order;
    
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    // public function createNewOrder($orderItems, $totalPrice, $user_id){
        

    // }

    public function getOrderById($id){
        return Order::findOrFail($id)->load(['order_items']);
    }

    public function getMyOrders($user_id){
        return Order::where('user_id', $user_id)->orderBy('order_date', 'desc')->get();
    }
}