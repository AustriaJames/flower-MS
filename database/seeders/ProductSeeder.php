<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();

        if (!$user) {
            $user = User::factory()->create([
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@bonasflowershop.com',
                'phone' => '+63 912 345 6789',
            ]);
        }

        $products = [
            [
                'name' => 'Sunshine Mix Bouquet',
                'description' => 'A vibrant mix of yellow and orange flowers perfect for brightening any day. This cheerful arrangement includes sunflowers, daisies, and chrysanthemums.',
                'short_description' => 'Colorful summer flowers',
                'price' => 2500.00,
                'sale_price' => null,
                'stock_quantity' => 50,
                'is_featured' => true,
                'category_name' => 'Bouquets',
            ],
            [
                'name' => 'Wedding Dream Collection',
                'description' => 'Elegant white and pink roses arranged in a romantic style, perfect for weddings and anniversaries. Includes baby\'s breath and greenery.',
                'short_description' => 'Perfect for special occasions',
                'price' => 3200.00,
                'sale_price' => 2800.00,
                'stock_quantity' => 30,
                'is_featured' => true,
                'category_name' => 'Wedding Flowers',
            ],
            [
                'name' => 'Spring Garden Arrangement',
                'description' => 'Fresh spring flowers including tulips, daffodils, and hyacinths arranged in a beautiful garden-style display.',
                'short_description' => 'Fresh spring blooms',
                'price' => 2800.00,
                'sale_price' => null,
                'stock_quantity' => 40,
                'is_featured' => false,
                'category_name' => 'Arrangements',
            ],
            [
                'name' => 'Luxury Rose Collection',
                'description' => 'Premium long-stemmed roses in various colors, perfect for expressing deep emotions and making a lasting impression.',
                'short_description' => 'Premium long-stemmed roses',
                'price' => 4500.00,
                'sale_price' => 3800.00,
                'stock_quantity' => 25,
                'is_featured' => true,
                'category_name' => 'Single Flowers',
            ],
            [
                'name' => 'Autumn Harvest Mix',
                'description' => 'Warm autumn colors featuring orange, red, and yellow flowers including marigolds, zinnias, and dahlias.',
                'short_description' => 'Warm autumn colors',
                'price' => 2200.00,
                'sale_price' => null,
                'stock_quantity' => 35,
                'is_featured' => false,
                'category_name' => 'Seasonal Flowers',
            ],
            [
                'name' => 'Birthday Celebration Set',
                'description' => 'Complete birthday gift package including a colorful flower arrangement, birthday card, and small gift box.',
                'short_description' => 'Complete birthday package',
                'price' => 3500.00,
                'sale_price' => 3000.00,
                'stock_quantity' => 20,
                'is_featured' => true,
                'category_name' => 'Gift Sets',
            ],
        ];

        foreach ($products as $product) {
            $category = Category::where('name', $product['category_name'])->first();

            if ($category) {
                Product::create([
                    'name' => $product['name'],
                    'slug' => Str::slug($product['name']),
                    'description' => $product['description'],
                    'short_description' => $product['short_description'],
                    'price' => $product['price'],
                    'sale_price' => $product['sale_price'],
                    'stock_quantity' => $product['stock_quantity'],
                    'in_stock' => $product['stock_quantity'] > 0,
                    'is_featured' => $product['is_featured'],
                    'is_active' => true,
                    'main_image' => null,
                    'gallery_images' => [],
                    'specifications' => [
                        'vase_included' => true,
                        'care_instructions' => 'Keep in cool place, change water daily',
                        'delivery_time' => 'Same day delivery available',
                    ],
                    'sort_order' => 0,
                    'category_id' => $category->id,
                    'created_by' => $user->id,
                ]);
            }
        }
    }
}
