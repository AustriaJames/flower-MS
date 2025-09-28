<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bouquets',
                'slug' => 'bouquets',
                'description' => 'Beautiful flower arrangements perfect for any occasion',
                'icon' => 'bi bi-flower1',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Plants',
                'slug' => 'plants',
                'description' => 'Indoor and outdoor plants to brighten your space',
                'icon' => 'bi bi-seedling',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Gifts',
                'slug' => 'gifts',
                'description' => 'Special gift items and accessories',
                'icon' => 'bi bi-star',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Wedding',
                'slug' => 'wedding',
                'description' => 'Elegant wedding flowers and decorations',
                'icon' => 'bi bi-heart',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Seasonal',
                'slug' => 'seasonal',
                'description' => 'Flowers and arrangements for special seasons',
                'icon' => 'bi bi-sun',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Holiday',
                'slug' => 'holiday',
                'description' => 'Festive arrangements for holidays and celebrations',
                'icon' => 'bi bi-gift',
                'sort_order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
