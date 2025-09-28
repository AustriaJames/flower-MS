<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Booking;
use App\Models\Review;
use App\Models\Chat;
use App\Models\ChatMessage;
use Carbon\Carbon;

class ComprehensiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting comprehensive seeding...');

        // Clear existing data
        $this->command->info('Clearing existing data...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::table('chat_messages')->truncate();
        DB::table('chats')->truncate();
        DB::table('reviews')->truncate();
        DB::table('bookings')->truncate();
        DB::table('order_items')->truncate();
        DB::table('orders')->truncate();
        DB::table('products')->truncate();
        DB::table('categories')->truncate();
        DB::table('users')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        // Create Admin Users
        $this->command->info('Creating admin users...');
        $admin1 = User::create([
            'name' => 'Admin User',
            'email' => 'admin@flowershop.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'first_name' => 'Admin',
            'last_name' => 'User',
            'phone' => '+639123456789',
        ]);

        $admin2 = User::create([
            'name' => 'Support Admin',
            'email' => 'support@flowershop.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_admin' => true,
            'first_name' => 'Support',
            'last_name' => 'Admin',
            'phone' => '+639123456790',
        ]);

        // Create Regular Customers
        $this->command->info('Creating customers...');
        $customers = [];
        for ($i = 1; $i <= 20; $i++) {
            $customers[] = User::create([
                'name' => "Customer {$i}",
                'email' => "customer{$i}@example.com",
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_admin' => false,
                'first_name' => "Customer",
                'last_name' => "{$i}",
                'phone' => '+63' . rand(900000000, 999999999),
            ]);
        }

        // Create Regular Categories
        $this->command->info('Creating regular categories...');
        $regularCategories = [
            [
                'name' => 'Roses',
                'description' => 'Beautiful roses in various colors and arrangements',
                'icon' => 'bi bi-flower1',
                'sort_order' => 1,
            ],
            [
                'name' => 'Tulips',
                'description' => 'Elegant tulips perfect for spring occasions',
                'icon' => 'bi bi-flower2',
                'sort_order' => 2,
            ],
            [
                'name' => 'Lilies',
                'description' => 'Stunning lilies for special moments',
                'icon' => 'bi bi-flower3',
                'sort_order' => 3,
            ],
            [
                'name' => 'Sunflowers',
                'description' => 'Bright and cheerful sunflowers',
                'icon' => 'bi bi-sun',
                'sort_order' => 4,
            ],
            [
                'name' => 'Mixed Bouquets',
                'description' => 'Beautiful mixed flower arrangements',
                'icon' => 'bi bi-bouquet',
                'sort_order' => 5,
            ],
        ];

        foreach ($regularCategories as $cat) {
            Category::create(array_merge($cat, [
                'slug' => Str::slug($cat['name']),
                'is_occasion' => false,
                'is_active' => true,
            ]));
        }

        // Create Occasion Categories
        $this->command->info('Creating occasion categories...');
        $occasionCategories = [
            [
                'name' => 'Valentine\'s Day',
                'description' => 'Romantic arrangements for Valentine\'s Day',
                'icon' => 'bi bi-heart',
                'occasion_date' => '2024-02-14',
                'sort_order' => 1,
            ],
            [
                'name' => 'Mother\'s Day',
                'description' => 'Special flowers for mothers',
                'icon' => 'bi bi-heart-fill',
                'occasion_date' => '2024-05-12',
                'sort_order' => 2,
            ],
            [
                'name' => 'Birthday',
                'description' => 'Colorful birthday arrangements',
                'icon' => 'bi bi-gift',
                'occasion_date' => null,
                'sort_order' => 3,
            ],
            [
                'name' => 'Wedding',
                'description' => 'Elegant wedding flowers',
                'icon' => 'bi bi-people',
                'occasion_date' => null,
                'sort_order' => 4,
            ],
            [
                'name' => 'Funeral',
                'description' => 'Respectful funeral arrangements',
                'icon' => 'bi bi-flower1',
                'occasion_date' => null,
                'sort_order' => 5,
            ],
        ];

        foreach ($occasionCategories as $cat) {
            Category::create(array_merge($cat, [
                'slug' => Str::slug($cat['name']),
                'is_occasion' => true,
                'is_active' => true,
            ]));
        }

        // Get all categories
        $categories = Category::all();

        // Create Products
        $this->command->info('Creating products...');
        $products = [];
        $productNames = [
            'Red Rose Bouquet', 'White Rose Bouquet', 'Pink Rose Bouquet',
            'Yellow Tulips', 'Red Tulips', 'Purple Tulips',
            'White Lilies', 'Pink Lilies', 'Orange Lilies',
            'Sunflower Bouquet', 'Mixed Spring Flowers', 'Romantic Roses',
            'Birthday Surprise', 'Wedding Bouquet', 'Sympathy Arrangement',
            'Valentine Special', 'Mother\'s Day Gift', 'Anniversary Roses',
            'Graduation Bouquet', 'Corporate Flowers'
        ];

        foreach ($productNames as $index => $name) {
            $category = $categories->random();
            $price = rand(500, 3000);
            $stock = rand(10, 100);

            $products[] = Product::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'description' => "Beautiful {$name} perfect for any occasion.",
                'short_description' => "Perfect {$name} for special moments.",
                'sku' => 'FLW-' . str_pad($index + 1, 4, '0', STR_PAD_LEFT),
                'price' => $price,
                'sale_price' => rand(0, 1) ? $price * 0.8 : null,
                'stock_quantity' => $stock,
                'in_stock' => $stock > 0,
                'category_id' => $category->id,
                'is_active' => true,
                'is_featured' => rand(0, 1),
                'sort_order' => $index + 1,
                'created_by' => $admin1->id,
                'specifications' => json_encode([
                    'color' => ['Red', 'White', 'Pink', 'Yellow'][array_rand([0, 1, 2, 3])],
                    'size' => ['Small', 'Medium', 'Large'][array_rand([0, 1, 2])],
                    'care_instructions' => 'Keep in a cool place and change water regularly'
                ]),
            ]);
        }

        // Create Orders
        $this->command->info('Creating orders...');
        $orders = [];
        for ($i = 1; $i <= 50; $i++) {
            $customer = $customers[array_rand($customers)];
            $orderDate = Carbon::now()->subDays(rand(1, 90));
            $statuses = ['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled'];
            $status = $statuses[array_rand($statuses)];

            $order = Order::create([
                'user_id' => $customer->id,
                'order_number' => 'ORD-' . str_pad($i, 6, '0', STR_PAD_LEFT),
                'status' => $status,
                'subtotal' => 0, // Will be calculated
                'tax_amount' => 0,
                'shipping_amount' => 100,
                'discount_amount' => 0,
                'total_amount' => 0, // Will be calculated
                'notes' => rand(0, 1) ? 'Special delivery instructions' : null,
                'order_date' => $orderDate,
                'estimated_delivery' => $orderDate->addDays(3),
                'delivered_at' => $status === 'delivered' ? $orderDate->addDays(3) : null,
                'created_at' => $orderDate,
                'updated_at' => $orderDate,
            ]);

            // Create Order Items
            $numItems = rand(1, 3);
            $totalAmount = 0;

            for ($j = 0; $j < $numItems; $j++) {
                $product = $products[array_rand($products)];
                $quantity = rand(1, 3);
                $price = $product->sale_price ?? $product->price;
                $totalAmount += $price * $quantity;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'unit_price' => $price,
                    'total_price' => $price * $quantity,
                ]);
            }

            $order->update(['total_amount' => $totalAmount]);
            $orders[] = $order;
        }

        // Create Bookings
        $this->command->info('Creating bookings...');
        $eventTypes = ['wedding', 'birthday', 'anniversary', 'graduation', 'funeral', 'corporate', 'other'];
        $statuses = ['pending', 'confirmed', 'rescheduled', 'cancelled', 'completed'];

        for ($i = 1; $i <= 30; $i++) {
            $customer = $customers[array_rand($customers)];
            $eventDate = Carbon::now()->addDays(rand(1, 365));
            $status = $statuses[array_rand($statuses)];

            Booking::create([
                'user_id' => $customer->id,
                'category_id' => $categories->random()->id,
                'customer_name' => $customer->name,
                'customer_email' => $customer->email,
                'customer_phone' => '+63' . rand(900000000, 999999999),
                'event_type' => $eventTypes[array_rand($eventTypes)],
                'event_date' => $eventDate,
                'event_time' => '14:00',
                'venue' => rand(0, 1) ? 'Sample Venue, Sample City' : null,
                'requirements' => rand(0, 1) ? 'Special requirements for the event' : null,
                'budget_range' => ['₱5,000-10,000', '₱10,000-20,000', '₱20,000-50,000'][array_rand([0, 1, 2])],
                'status' => $status,
                'admin_notes' => rand(0, 1) ? 'Admin notes for this booking' : null,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);
        }

                // Create Reviews
        $this->command->info('Creating reviews...');
        $statuses = ['pending', 'approved', 'rejected'];
        $reviewCount = 0;

        foreach ($customers as $customer) {
            foreach ($products as $product) {
                if ($reviewCount >= 50) break 2; // Limit to 50 reviews

                $order = $orders[array_rand($orders)];
                $status = $statuses[array_rand($statuses)];

                Review::create([
                    'user_id' => $customer->id,
                    'product_id' => $product->id,
                    'order_id' => $order->id,
                    'rating' => rand(1, 5),
                    'comment' => rand(0, 1) ? 'Great product! Highly recommended.' : 'Good quality flowers.',
                    'status' => $status,
                    'is_verified_purchase' => true,
                    'admin_response' => rand(0, 1) ? 'Thank you for your review!' : null,
                    'admin_notes' => rand(0, 1) ? 'Internal notes about this review' : null,
                    'reviewed_at' => $status !== 'pending' ? Carbon::now()->subDays(rand(1, 30)) : null,
                    'reviewed_by' => $status !== 'pending' ? $admin1->id : null,
                    'created_at' => Carbon::now()->subDays(rand(1, 60)),
                ]);

                $reviewCount++;
            }
        }

        // Create Chats
        $this->command->info('Creating chats...');
        $priorities = ['low', 'medium', 'high', 'urgent'];
        $statuses = ['open', 'in_progress', 'resolved', 'closed'];

        for ($i = 1; $i <= 25; $i++) {
            $customer = $customers[array_rand($customers)];
            $status = $statuses[array_rand($statuses)];
            $priority = $priorities[array_rand($priorities)];

            $chat = Chat::create([
                'user_id' => $customer->id,
                'subject' => 'Support Request #' . $i,
                'status' => $status,
                'priority' => $priority,
                'assigned_to' => rand(0, 1) ? $admin1->id : null,
                'assigned_at' => rand(0, 1) ? Carbon::now()->subDays(rand(1, 30)) : null,
                'resolved_at' => $status === 'resolved' ? Carbon::now()->subDays(rand(1, 30)) : null,
                'closed_at' => $status === 'closed' ? Carbon::now()->subDays(rand(1, 30)) : null,
                'closed_by' => $status === 'closed' ? $admin1->id : null,
                'admin_notes' => rand(0, 1) ? 'Admin notes for this chat' : null,
                'customer_notes' => rand(0, 1) ? 'Customer notes' : null,
                'created_at' => Carbon::now()->subDays(rand(1, 30)),
            ]);

            // Create Chat Messages
            $numMessages = rand(2, 8);
            for ($j = 0; $j < $numMessages; $j++) {
                $isAdmin = $j % 2 === 1; // Alternate between customer and admin
                $sender = $isAdmin ? $admin1 : $customer;

                ChatMessage::create([
                    'chat_id' => $chat->id,
                    'user_id' => $sender->id,
                    'message' => $isAdmin ? 'Thank you for contacting us. How can I help you?' : 'I have a question about my order.',
                    'is_admin' => $isAdmin,
                    'is_read' => true,
                    'message_type' => 'text',
                    'created_at' => $chat->created_at->addMinutes($j * 5),
                ]);
            }
        }

        $this->command->info('Comprehensive seeding completed successfully!');
        $this->command->info('Admin login: admin@flowershop.com / password');
        $this->command->info('Support login: support@flowershop.com / password');
        $this->command->info('Customer login: customer1@example.com / password');
    }
}
