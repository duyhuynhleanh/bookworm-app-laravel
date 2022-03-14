<?php
namespace App\Services;

use App\Interfaces\OrderInterface;


class OrderService
{
    protected $order;
    
    public function __construct(OrderInterface $order)
    {
        $this->order = $order;
    }

    // public function createNewOrder($orderItems, $totalPrice, $user_id){
    //     $order = $this->createNewOrder($orderItems, $totalPrice, $user_id);
    //     if ($order) {
    //         return $order;
    //     } else {
    //         return response()->json(['message' => 'No order items'], 400);
    //     }
    // }

    public function getOrderById($id){
        $order = $this->order->getOrderById($id);
        if($order) {
            return $order;
        } else {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }


    public function getMyOrders($user_id){
        $orders = $this->order->getMyOrders($user_id);
        if($orders) {
            return $orders;
        } else {
            return response()->json(['message' => 'Orders not found'], 404);
        }
    }

}