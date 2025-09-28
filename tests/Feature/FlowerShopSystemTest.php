<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlowerShopSystemTest extends TestCase
{
    use RefreshDatabase;

    public function test_categories_can_be_created_and_retrieved()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'A test category for testing purposes',
            'icon' => 'bi bi-test',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('categories', [
            'name' => 'Test Category',
            'slug' => 'test-category',
        ]);

        $this->assertEquals('Test Category', $category->name);
    }

    public function test_products_can_be_created_with_category_relationship()
    {
        $user = User::factory()->create([
            'first_name' => 'Test',
            'last_name' => 'User',
            'email' => 'test@example.com',
            'phone' => '+63 912 345 6789',
        ]);

        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'description' => 'A test category for testing purposes',
            'icon' => 'bi bi-test',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $product = Product::create([
            'name' => 'Test Product',
            'slug' => 'test-product',
            'description' => 'Test product description',
            'short_description' => 'Short description',
            'price' => 1000.00,
            'sku' => 'TEST-001',
            'stock_quantity' => 10,
            'in_stock' => true,
            'is_featured' => false,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'sku' => 'TEST-001',
        ]);

        // Test relationships
        $this->assertEquals($category->id, $product->category->id);
        $this->assertEquals($user->id, $product->creator->id);
        $this->assertEquals('Test Category', $product->category->name);
        $this->assertEquals($user->name, $product->creator->name);
    }

    public function test_product_scopes_work_correctly()
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        // Create active and inactive products
        Product::create([
            'name' => 'Active Product',
            'slug' => 'active-product',
            'price' => 1000.00,
            'sku' => 'ACTIVE-001',
            'stock_quantity' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        Product::create([
            'name' => 'Inactive Product',
            'slug' => 'inactive-product',
            'price' => 1000.00,
            'sku' => 'INACTIVE-001',
            'stock_quantity' => 10,
            'is_active' => false,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        // Test active scope
        $activeProducts = Product::active()->get();
        $this->assertEquals(1, $activeProducts->count());
        $this->assertEquals('Active Product', $activeProducts->first()->name);

        // Test featured scope
        $featuredProduct = Product::create([
            'name' => 'Featured Product',
            'slug' => 'featured-product',
            'price' => 1000.00,
            'sku' => 'FEATURED-001',
            'stock_quantity' => 10,
            'is_featured' => true,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        $featuredProducts = Product::featured()->get();
        $this->assertEquals(1, $featuredProducts->count());
        $this->assertEquals('Featured Product', $featuredProducts->first()->name);
    }

    public function test_product_price_attributes_work_correctly()
    {
        $user = User::factory()->create();
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        // Product with sale price
        $saleProduct = Product::create([
            'name' => 'Sale Product',
            'slug' => 'sale-product',
            'price' => 1000.00,
            'sale_price' => 800.00,
            'sku' => 'SALE-001',
            'stock_quantity' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        // Product without sale price
        $regularProduct = Product::create([
            'name' => 'Regular Product',
            'slug' => 'regular-product',
            'price' => 1000.00,
            'sku' => 'REGULAR-001',
            'stock_quantity' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        // Test current price
        $this->assertEquals(800.00, $saleProduct->current_price);
        $this->assertEquals(1000.00, $regularProduct->current_price);

        // Test is on sale
        $this->assertTrue($saleProduct->is_on_sale);
        $this->assertFalse($regularProduct->is_on_sale);

        // Test discount percentage
        $this->assertEquals(20, $saleProduct->discount_percentage);
        $this->assertEquals(0, $regularProduct->discount_percentage);
    }

    public function test_category_relationships_work_correctly()
    {
        $category = Category::create([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'is_active' => true,
        ]);

        $user = User::factory()->create();

        // Create products for this category
        Product::create([
            'name' => 'Product 1',
            'slug' => 'product-1',
            'price' => 1000.00,
            'sku' => 'PROD-001',
            'stock_quantity' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        Product::create([
            'name' => 'Product 2',
            'slug' => 'product-2',
            'price' => 2000.00,
            'sku' => 'PROD-002',
            'stock_quantity' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'created_by' => $user->id,
        ]);

        // Test category has products
        $this->assertEquals(2, $category->products->count());
        $this->assertEquals('Product 1', $category->products->first()->name);
        $this->assertEquals('Product 2', $category->products->last()->name);
    }
}
