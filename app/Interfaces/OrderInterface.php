<?php

namespace App\Interfaces;

interface OrderInterface
{
    //public function createNewOrder($orderItems, $totalPrice, $user_id);
    public function getMyOrders($user_id);
    public function getOrderById($id);
}
