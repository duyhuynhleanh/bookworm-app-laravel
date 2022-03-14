<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Services\OrderService;
use Illuminate\Support\Facades\Redis;

class OrderController extends Controller
{
    protected $order;
    public function __construct(OrderService $order)
    {
        $this->order = $order;
    }

    // @desc    Create new order
    // @route   POST /api/orders
    // @access  Private
    public function createNewOrder(Request $request){
        $user_id = $request->user()->id;
        $totalPrice = $request->totalPrice;
        $orderItems = $request->orderItems;
        $createdOrder = Order::create([
            'order_date' => now(),
            'order_amount' => $totalPrice,
            'user_id' => $user_id,
        ]);
        $createdOrder->order_items()->createMany($orderItems);
        return $createdOrder;
    }

    public function getOrderById($id){
        return $this->order->getOrderById($id);   
    }

    // @desc    Get logged in user orders
    // @route   GET /api/orders/myorders
    // @access  Private
    public function getMyOrders(Request $request){
        $user_id = $request->user()->id;
        return $this->order->getMyOrders($user_id);
    }
}
