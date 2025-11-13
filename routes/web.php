<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\TrackingController;
use App\Http\Controllers\ChatController;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('welcome');
Route::get('/home', [HomeController::class, 'index'])->name('home');

// Product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/category/{category}', [ProductController::class, 'byCategory'])->name('products.byCategory');

// Category routes
Route::get('/categories', [CategoryController::class, 'customerIndex'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'customerShow'])->name('categories.show');
Route::get('/occasions', [CategoryController::class, 'customerOccasions'])->name('occasions.index');

// Search routes
Route::get('/search', [ProductController::class, 'search'])->name('products.search');

// Authentication routes (excluding login/register since they're handled via modals)
Auth::routes(['login' => false, 'register' => false]);

// Custom authentication routes for modal forms
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');
Route::post('/register', [App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

// Protected customer routes
Route::middleware(['auth'])->group(function () {
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/update/{cartItemId}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/remove/{cartItemId}', [CartController::class, 'remove'])->name('cart.remove');

    // Wishlist routes
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/add', [WishlistController::class, 'add'])->name('wishlist.add');
    Route::delete('/wishlist/remove/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');

    // Order routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'requestCancellation'])->name('orders.requestCancellation');

    // Test route for debugging orders
    Route::get('/test-orders', function() {
        $orders = App\Models\Order::all();
        return response()->json([
            'orders' => $orders->map(function($order) {
                return [
                    'id' => $order->id,
                    'status' => $order->status,
                    'user_id' => $order->user_id,
                    'cancellation_requested' => $order->cancellation_requested
                ];
            })
        ]);
    })->name('orders.test');

    // Debug route for cancellation testing
    Route::get('/debug-cancellation', function() {
        $orders = App\Models\Order::where('user_id', Auth::id())->get();
        return view('customer.orders.debug', compact('orders'));
    })->name('orders.debug');

    // Booking routes
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');

    // Review routes
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');

    // Chat routes
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.sendMessage');
    Route::get('/chat/new-messages', [ChatController::class, 'getNewMessages'])->name('chat.getNewMessages');
    Route::post('/chat/mark-read', [ChatController::class, 'markAsRead'])->name('chat.markAsRead');

    // Profile routes
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::put('/profile', [HomeController::class, 'updateProfile'])->name('profile.update');
});

// Tracking routes (public)
    Route::get('/track', [TrackingController::class, 'index'])->name('tracking.index');
    Route::get('/track/search', [TrackingController::class, 'track'])->name('tracking.track');
    Route::get('/track/{tracking_number}', [TrackingController::class, 'trackByNumber'])->name('tracking.track.number');

// Include admin routes
require __DIR__.'/admin.php';
