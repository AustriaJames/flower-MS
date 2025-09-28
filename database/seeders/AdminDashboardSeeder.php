<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Chat;
use App\Models\ChatMessage;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Str;

class AdminDashboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample occasion categories
        $occasionCategories = [
            ['name' => 'Valentine\'s Day', 'is_occasion' => true, 'occasion_date' => '2025-02-14'],
            ['name' => 'Mother\'s Day', 'is_occasion' => true, 'occasion_date' => '2025-05-11'],
            ['name' => 'Weddings', 'is_occasion' => true, 'occasion_date' => null],
            ['name' => 'Birthdays', 'is_occasion' => true, 'occasion_date' => null],
            ['name' => 'Funerals', 'is_occasion' => true, 'occasion_date' => null],
        ];

        foreach ($occasionCategories as $categoryData) {
            Category::create(array_merge($categoryData, [
                'slug' => Str::slug($categoryData['name']),
                'description' => 'Special flowers for ' . $categoryData['name'],
                'is_active' => true,
                'sort_order' => 0,
            ]));
        }

        // Create sample regular categories
        $regularCategories = [
            'Roses', 'Tulips', 'Lilies', 'Sunflowers', 'Orchids'
        ];

        foreach ($regularCategories as $index => $categoryName) {
            Category::create([
                'name' => $categoryName,
                'slug' => Str::slug($categoryName),
                'description' => 'Beautiful ' . strtolower($categoryName),
                'is_active' => true,
                'sort_order' => $index + 1,
                'is_occasion' => false,
            ]);
        }

        // Create sample products
        $categories = Category::where('is_occasion', false)->get();
        foreach ($categories as $category) {
            for ($i = 1; $i <= 3; $i++) {
                Product::create([
                    'name' => $category->name . ' ' . $i,
                    'slug' => Str::slug($category->name . ' ' . $i),
                    'sku' => 'SKU-' . strtoupper($category->name) . '-' . $i,
                    'description' => 'Beautiful ' . strtolower($category->name) . ' arrangement',
                    'price' => rand(500, 2000),
                    'stock_quantity' => rand(10, 50),
                    'in_stock' => true,
                    'is_active' => true,
                    'category_id' => $category->id,
                    'created_by' => 1,
                ]);
            }
        }

        // Create sample bookings
        $eventTypes = ['wedding', 'birthday', 'anniversary', 'graduation', 'funeral', 'corporate'];
        $statuses = ['pending', 'confirmed', 'rescheduled', 'cancelled', 'completed'];

        for ($i = 1; $i <= 10; $i++) {
            Booking::create([
                'customer_name' => 'Customer ' . $i,
                'customer_email' => 'customer' . $i . '@example.com',
                'customer_phone' => '+1234567890',
                'event_type' => $eventTypes[array_rand($eventTypes)],
                'event_date' => now()->addDays(rand(1, 30)),
                'event_time' => '14:00',
                'venue' => 'Venue ' . $i,
                'requirements' => 'Sample requirements for event ' . $i,
                'budget_range' => '₱' . rand(1000, 5000),
                'status' => $statuses[array_rand($statuses)],
                'user_id' => 1,
            ]);
        }

        // Create sample reviews
        $products = Product::take(5)->get();
        foreach ($products as $product) {
            for ($i = 1; $i <= 3; $i++) {
                Review::create([
                    'user_id' => 1,
                    'product_id' => $product->id,
                    'rating' => rand(3, 5),
                    'comment' => 'Great product! Sample review ' . $i,
                    'status' => ['pending', 'approved', 'rejected'][array_rand(['pending', 'approved', 'rejected'])],
                    'is_approved' => false,
                    'is_verified_purchase' => true,
                ]);
            }
        }

        // Create sample chats
        for ($i = 1; $i <= 5; $i++) {
            $chat = Chat::create([
                'user_id' => 1,
                'subject' => 'Support Request ' . $i,
                'status' => ['open', 'in_progress', 'resolved', 'closed'][array_rand(['open', 'in_progress', 'resolved', 'closed'])],
                'priority' => ['low', 'medium', 'high', 'urgent'][array_rand(['low', 'medium', 'high', 'urgent'])],
            ]);

            // Create sample messages for each chat
            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => 1,
                'message' => 'Hello, I need help with order #' . $i,
                'is_admin' => false,
                'is_read' => true,
            ]);

            ChatMessage::create([
                'chat_id' => $chat->id,
                'user_id' => 1,
                'message' => 'Thank you for contacting us. How can I help you?',
                'is_admin' => true,
                'is_read' => false,
            ]);
        }
    }
}
