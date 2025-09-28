<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReportsController extends Controller
{
    /**
     * Display sales reports dashboard
     */
    public function sales(Request $request)
    {
        $period = $request->get('period', 'month');
        $dateFrom = $request->get('date_from', $this->getStartDate($period)->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $startDate = Carbon::parse($dateFrom);
        $endDate = Carbon::parse($dateTo);

        // Sales overview
        $salesData = $this->getSalesData($startDate, $endDate, $period);

        // Top selling products
        $topProducts = $this->getTopSellingProducts($startDate, $endDate);

        // Sales by category
        $salesByCategory = $this->getSalesByCategory($startDate, $endDate);

        // Daily/Weekly/Monthly trends
        $trends = $this->getSalesTrends($startDate, $endDate, $period);

        // Recent orders for display
        $recentOrders = Order::with(['user', 'orderItems'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.reports.sales', compact(
            'salesData',
            'topProducts',
            'salesByCategory',
            'trends',
            'period',
            'dateFrom',
            'dateTo',
            'recentOrders'
        ));
    }

    /**
     * Display inventory reports
     */
    public function inventory()
    {
        // Low stock products
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('is_active', true)
            ->orderBy('stock_quantity')
            ->get();

        // Out of stock products
        $outOfStockProducts = Product::where('stock_quantity', 0)
            ->where('is_active', true)
            ->get();

        // Stock value by category
        $stockByCategory = Category::with('products')
            ->get()
            ->map(function ($category) {
                $totalValue = $category->products->sum(function ($product) {
                    return $product->stock_quantity * $product->price;
                });

                return [
                    'category' => $category->name,
                    'total_products' => $category->products->count(),
                    'total_stock' => $category->products->sum('stock_quantity'),
                    'total_value' => $totalValue,
                ];
            });

        // Products with high turnover
        $highTurnoverProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.reports.inventory', compact(
            'lowStockProducts',
            'outOfStockProducts',
            'stockByCategory',
            'highTurnoverProducts'
        ));
    }

    /**
     * Display customer reports
     */
    public function customers(Request $request)
    {
        $period = $request->get('period', 'year');
        $startDate = $this->getStartDate($period);

        // Customer overview
        $customerStats = [
            'total_customers' => User::count(),
            'new_customers' => User::where('created_at', '>=', $startDate)->count(),
            'active_customers' => User::whereHas('orders', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })->count(),
            'repeat_customers' => User::whereHas('orders', function ($query) {
                $query->havingRaw('COUNT(*) > 1');
            })->count(),
        ];

        // Top customers by spending
        $topCustomers = User::withCount('orders')
            ->withSum('orders', 'total_amount')
            ->orderBy('orders_sum_total_amount', 'desc')
            ->take(10)
            ->get();

        // Customer retention rate
        $retentionRate = $this->calculateRetentionRate($startDate);

        // Customer lifetime value
        $customerLTV = User::withSum('orders', 'total_amount')
            ->withCount('orders')
            ->whereHas('orders')
            ->get()
            ->avg('orders_sum_total_amount');

        // Customer segments
        $customerSegments = $this->getCustomerSegments();

        return view('admin.reports.customers', compact(
            'customerStats',
            'topCustomers',
            'retentionRate',
            'customerLTV',
            'customerSegments',
            'period'
        ));
    }

    /**
     * Display product performance reports
     */
    public function products(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);

        // Product performance metrics
        $productMetrics = Product::withCount('orderItems')
            ->withSum('orderItems', 'quantity')
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'category' => $product->category->name ?? 'N/A',
                    'total_sales' => $product->order_items_count,
                    'total_quantity' => $product->order_items_sum_quantity ?? 0,
                    'avg_rating' => round($product->reviews_avg_rating ?? 0, 1),
                    'review_count' => $product->reviews_count,
                    'revenue' => $product->orderItems->sum(function ($item) {
                        return $item->quantity * $item->price;
                    }),
                    'stock_level' => $product->stock_quantity,
                    'status' => $product->is_active ? 'Active' : 'Inactive',
                ];
            })
            ->sortByDesc('total_sales');

        // Category performance
        $categoryPerformance = Category::with('products')
            ->get()
            ->map(function ($category) use ($startDate) {
                $products = $category->products;
                $totalSales = $products->sum(function ($product) {
                    return $product->orderItems->sum('quantity');
                });

                $totalRevenue = $products->sum(function ($product) {
                    return $product->orderItems->sum(function ($item) {
                        return $item->quantity * $item->price;
                    });
                });

                return [
                    'category' => $category->name,
                    'total_products' => $products->count(),
                    'total_sales' => $totalSales,
                    'total_revenue' => $totalRevenue,
                    'avg_rating' => $products->avg('reviews.rating') ?? 0,
                ];
            })
            ->sortByDesc('total_revenue');

        // Product statistics
        $productStats = [
            'total_products' => Product::count(),
            'total_sales' => Product::withCount('orderItems')->get()->sum('order_items_count'),
            'total_revenue' => Product::with('orderItems')->get()->sum(function ($product) {
                return $product->orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
            }),
            'avg_rating' => Product::withAvg('reviews', 'rating')->get()->avg('reviews_avg_rating') ?? 0,
            'low_stock_count' => Product::where('stock_quantity', '<=', 10)->count(),
        ];

        // Top selling products
        $topSellingProducts = Product::with(['category', 'orderItems', 'reviews'])
            ->get()
            ->map(function ($product) {
                $unitsSold = $product->orderItems->sum('quantity');
                $revenue = $product->orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                $avgRating = $product->reviews->avg('rating') ?? 0;

                return (object) [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'main_image' => $product->main_image,
                    'category' => $product->category,
                    'stock_quantity' => $product->stock_quantity,
                    'units_sold' => $unitsSold,
                    'revenue' => $revenue,
                    'avg_rating' => $avgRating,
                ];
            })
            ->sortByDesc('units_sold')
            ->take(10);

        // Top rated products
        $topRatedProducts = Product::with(['category', 'reviews'])
            ->get()
            ->map(function ($product) {
                $avgRating = $product->reviews->avg('rating') ?? 0;

                return (object) [
                    'id' => $product->id,
                    'name' => $product->name,
                    'sku' => $product->sku,
                    'main_image' => $product->main_image,
                    'category' => $product->category,
                    'avg_rating' => $avgRating,
                ];
            })
            ->sortByDesc('avg_rating')
            ->take(10);

        // Category performance
        $categoryPerformance = Category::with('products')
            ->get()
            ->map(function ($category) {
                $products = $category->products;
                $revenue = $products->sum(function ($product) {
                    return $product->orderItems->sum(function ($item) {
                        return $item->quantity * $item->price;
                    });
                });

                $salesCount = $products->sum(function ($product) {
                    return $product->orderItems->count();
                });

                return [
                    'name' => $category->name,
                    'products_count' => $products->count(),
                    'revenue' => $revenue,
                    'sales_count' => $salesCount,
                ];
            })
            ->sortByDesc('revenue');

        return view('admin.reports.products', compact(
            'productStats',
            'topSellingProducts',
            'topRatedProducts',
            'categoryPerformance',
            'period'
        ));
    }

    /**
     * Export sales report
     */
    public function exportSales(Request $request)
    {
        $period = $request->get('period', 'month');
        $startDate = $this->getStartDate($period);
        $endDate = now();

        $orders = Order::with(['user', 'orderItems.product'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get();

        $filename = 'sales_report_' . $period . '_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($orders) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'Order ID', 'Customer', 'Date', 'Items', 'Subtotal', 'Tax',
                'Shipping', 'Discount', 'Total', 'Status'
            ]);

            // Add data
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->user->name ?? 'Guest',
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->orderItems->count(),
                    $order->subtotal,
                    $order->tax_amount,
                    $order->shipping_amount,
                    $order->discount_amount,
                    $order->total_amount,
                    $order->status,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get start date based on period
     */
    private function getStartDate($period)
    {
        return match($period) {
            'week' => now()->subWeek(),
            'month' => now()->subMonth(),
            'quarter' => now()->subQuarter(),
            'year' => now()->subYear(),
            default => now()->subMonth(),
        };
    }

    /**
     * Get previous period start date
     */
    private function getPreviousPeriodStart($startDate, $period)
    {
        return match($period) {
            'daily' => $startDate->copy()->subDay(),
            'weekly' => $startDate->copy()->subWeek(),
            'monthly' => $startDate->copy()->subMonth(),
            'quarterly' => $startDate->copy()->subQuarter(),
            'yearly' => $startDate->copy()->subYear(),
            default => $startDate->copy()->subMonth(),
        };
    }

    /**
     * Get sales data for the period
     */
    private function getSalesData($startDate, $endDate, $period)
    {
        $orders = Order::whereBetween('created_at', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled');

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('total_amount');
        $averageOrderValue = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        // Calculate growth rate (comparing to previous period)
        $previousStartDate = $this->getPreviousPeriodStart($startDate, $period);
        $previousEndDate = $startDate->copy();
        $previousRevenue = Order::whereBetween('created_at', [$previousStartDate, $previousEndDate])
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $growthRate = $previousRevenue > 0 ? (($totalRevenue - $previousRevenue) / $previousRevenue) * 100 : 0;

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'average_order_value' => $averageOrderValue,
            'total_items_sold' => $orders->with('orderItems')->get()->sum(function ($order) {
                return $order->orderItems->sum('quantity');
            }),
            'growth_rate' => $growthRate,
        ];
    }

    /**
     * Get top selling products
     */
    private function getTopSellingProducts($startDate, $endDate)
    {
        return Product::withCount(['orderItems' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->orderBy('order_items_count', 'desc')
            ->take(10)
            ->get();
    }

    /**
     * Get sales by category
     */
    private function getSalesByCategory($startDate, $endDate)
    {
        $categories = Category::with(['products.orderItems' => function ($query) use ($startDate, $endDate) {
                $query->whereHas('order', function ($q) use ($startDate, $endDate) {
                    $q->whereBetween('created_at', [$startDate, $endDate]);
                });
            }])
            ->get();

        $labels = [];
        $data = [];

        foreach ($categories as $category) {
            $totalRevenue = $category->products->sum(function ($product) {
                return $product->orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
            });

            if ($totalRevenue > 0) {
                $labels[] = $category->name;
                $data[] = $totalRevenue;
            }
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Get sales trends
     */
    private function getSalesTrends($startDate, $endDate, $period)
    {
        $trends = [];

        if ($period === 'daily') {
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                $revenue = Order::whereDate('created_at', $currentDate)
                    ->where('status', '!=', 'cancelled')
                    ->sum('total_amount');

                $trends['labels'][] = $currentDate->format('M d');
                $trends['data'][] = $revenue;

                $currentDate->addDay();
            }
        } elseif ($period === 'weekly') {
            $currentDate = $startDate->copy()->startOfWeek();
            while ($currentDate <= $endDate) {
                $revenue = Order::whereBetween('created_at', [
                    $currentDate->copy()->startOfWeek(),
                    $currentDate->copy()->endOfWeek()
                ])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

                $trends['labels'][] = 'Week ' . $currentDate->format('M d');
                $trends['data'][] = $revenue;

                $currentDate->addWeek();
            }
        } elseif ($period === 'monthly') {
            $currentDate = $startDate->copy()->startOfMonth();
            while ($currentDate <= $endDate) {
                $revenue = Order::whereBetween('created_at', [
                    $currentDate->copy()->startOfMonth(),
                    $currentDate->copy()->endOfMonth()
                ])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

                $trends['labels'][] = $currentDate->format('M Y');
                $trends['data'][] = $revenue;

                $currentDate->addMonth();
            }
        } else {
            // Default to monthly
            $currentDate = $startDate->copy()->startOfMonth();
            while ($currentDate <= $endDate) {
                $revenue = Order::whereBetween('created_at', [
                    $currentDate->copy()->startOfMonth(),
                    $currentDate->copy()->endOfMonth()
                ])
                ->where('status', '!=', 'cancelled')
                ->sum('total_amount');

                $trends['labels'][] = $currentDate->format('M Y');
                $trends['data'][] = $revenue;

                $currentDate->addMonth();
            }
        }

        return $trends;
    }

    /**
     * Calculate customer retention rate
     */
    private function calculateRetentionRate($startDate)
    {
        $totalCustomers = User::where('created_at', '<', $startDate)->count();
        $returningCustomers = User::where('created_at', '<', $startDate)
            ->whereHas('orders', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->count();

        return $totalCustomers > 0 ? round(($returningCustomers / $totalCustomers) * 100, 2) : 0;
    }

    /**
     * Get customer segments
     */
    private function getCustomerSegments()
    {
        $customers = User::withSum('orders', 'total_amount')
            ->withCount('orders')
            ->whereHas('orders')
            ->get();

        return [
            'vip' => $customers->where('orders_sum_total_amount', '>=', 10000)->count(),
            'regular' => $customers->whereBetween('orders_sum_total_amount', [1000, 9999])->count(),
            'occasional' => $customers->whereBetween('orders_sum_total_amount', [100, 999])->count(),
            'new' => $customers->where('orders_sum_total_amount', '<', 100)->count(),
        ];
    }
}
