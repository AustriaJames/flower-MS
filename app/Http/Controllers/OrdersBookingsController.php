<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Booking;

class OrdersBookingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)->with('orderItems')->orderBy('created_at', 'desc')->get();
        $bookings = Booking::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        return view('customer.orders_bookings', compact('orders', 'bookings'));
    }
}
