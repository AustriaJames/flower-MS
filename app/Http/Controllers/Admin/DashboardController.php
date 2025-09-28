<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Get basic statistics
        $stats = [
            'total_users' => User::count(),
            'total_products' => Product::count(),
            'total_categories' => Category::count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'completed_orders' => Order::where('status', 'delivered')->count(),
            'total_revenue' => Order::where('status', 'delivered')->sum('total_amount'),
            'total_bookings' => \App\Models\Booking::count(),
            'pending_bookings' => \App\Models\Booking::where('status', 'pending')->count(),
            'total_reviews' => \App\Models\Review::count(),
            'pending_reviews' => \App\Models\Review::where('status', 'pending')->count(),
            'open_chats' => \App\Models\Chat::whereIn('status', ['open', 'in_progress'])->count(),
        ];

        // Get recent orders
        $recentOrders = Order::with(['user', 'orderItems.product'])
            ->latest()
            ->take(5)
            ->get();

        // Get top selling products
        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        // Get monthly revenue data for chart
        $monthlyRevenue = Order::where('status', 'delivered')
            ->selectRaw('MONTH(order_date) as month, SUM(total_amount) as revenue')
            ->whereYear('order_date', date('Y'))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Get order status distribution
        $orderStatuses = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'topProducts',
            'monthlyRevenue',
            'orderStatuses'
        ));
    }
}
