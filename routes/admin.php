<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\Admin\ReportsController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Products Management
    Route::resource('products', ProductController::class);
    Route::patch('products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::patch('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

    // Categories Management
    Route::get('categories/occasions', [CategoryController::class, 'occasions'])->name('categories.occasions');
    Route::post('categories/reorder', [CategoryController::class, 'reorder'])->name('categories.reorder');
    Route::resource('categories', CategoryController::class);
    Route::patch('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Orders Management
    Route::resource('orders', OrderController::class)->except(['create', 'store']);
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/approve-cancellation', [OrderController::class, 'approveCancellation'])->name('orders.approve-cancellation');
    Route::patch('orders/{order}/reject-cancellation', [OrderController::class, 'rejectCancellation'])->name('orders.reject-cancellation');
    Route::post('orders/{order}/create-tracking', [OrderController::class, 'createTracking'])->name('orders.create-tracking');
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');

    // Bookings Management
    Route::get('bookings/calendar', [BookingController::class, 'calendar'])->name('bookings.calendar');
    Route::get('bookings/export', [BookingController::class, 'export'])->name('bookings.export');
    Route::resource('bookings', BookingController::class)->except(['create', 'store']);
    Route::patch('bookings/{booking}/status', [BookingController::class, 'updateStatus'])->name('bookings.update-status');
    Route::patch('bookings/{booking}/reschedule', [BookingController::class, 'reschedule'])->name('bookings.reschedule');
    Route::patch('bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('bookings.cancel');

    // Reviews Management
    Route::resource('reviews', ReviewController::class)->except(['create', 'store']);
    Route::patch('reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');
    Route::patch('reviews/{review}/reply', [ReviewController::class, 'reply'])->name('reviews.reply');
    Route::post('reviews/bulk-action', [ReviewController::class, 'bulkAction'])->name('reviews.bulk-action');
    Route::get('reviews/export', [ReviewController::class, 'export'])->name('reviews.export');

    // Chat Support Management
    Route::resource('chats', ChatController::class)->except(['create', 'store', 'edit', 'update']);
    Route::post('chats/{chat}/message', [ChatController::class, 'sendMessage'])->name('chats.send-message');
    Route::patch('chats/{chat}/status', [ChatController::class, 'updateStatus'])->name('chats.update-status');
    Route::patch('chats/{chat}/assign', [ChatController::class, 'assign'])->name('chats.assign');
    Route::patch('chats/{chat}/close', [ChatController::class, 'close'])->name('chats.close');
    Route::patch('chats/{chat}/reopen', [ChatController::class, 'reopen'])->name('chats.reopen');
    Route::get('chats/{chat}/export', [ChatController::class, 'export'])->name('chats.export');
    Route::get('chats/statistics', [ChatController::class, 'statistics'])->name('chats.statistics');

    // Users Management
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/toggle-admin', [UserController::class, 'toggleAdmin'])->name('users.toggle-admin');
    Route::patch('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
    Route::get('users/export', [UserController::class, 'export'])->name('users.export');

    // Reports and Analytics
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('sales', [ReportsController::class, 'sales'])->name('sales');
        Route::get('inventory', [ReportsController::class, 'inventory'])->name('inventory');
        Route::get('customers', [ReportsController::class, 'customers'])->name('customers');
        Route::get('products', [ReportsController::class, 'products'])->name('products');
        Route::get('sales/export', [ReportsController::class, 'exportSales'])->name('sales.export');
    });
});
