<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true)
            ->where('in_stock', true)
            ->with(['category', 'reviews']);

        // Apply filters
        if ($request->category) {
            $query->where('category_id', $request->category);
        }

        if ($request->price_min) {
            $query->where('price', '>=', $request->price_min);
        }

        if ($request->price_max) {
            $query->where('price', '<=', $request->price_max);
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Apply sorting
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        switch ($sort) {
            case 'price':
                $query->orderBy('price', $direction);
                break;
            case 'rating':
                $query->orderBy('rating', $direction);
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            default:
                $query->orderBy('name', $direction);
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_occasion', false)->get();

        return view('customer.products.index', compact('products', 'categories'));
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        if (!$product->is_active || !$product->in_stock) {
            abort(404);
        }

        $product->load(['category', 'reviews' => function($query) {
            $query->approved()->with('user');
        }]);

        // Get related products
        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->take(4)
            ->get();

        return view('customer.products.show', compact('product', 'relatedProducts'));
    }

    /**
     * Display products by category.
     */
    public function byCategory(Category $category)
    {
        $products = Product::where('category_id', $category->id)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with(['category', 'reviews'])
            ->paginate(12);

        return view('customer.products.byCategory', compact('products', 'category'));
    }

    /**
     * Search products.
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2'
        ]);

        $query = $request->get('q');

        $products = Product::where('is_active', true)
            ->where('in_stock', true)
            ->where(function($q) use ($query) {
                $q->where('name', 'like', '%' . $query . '%')
                  ->orWhere('description', 'like', '%' . $query . '%')
                  ->orWhereHas('category', function($cat) use ($query) {
                      $cat->where('name', 'like', '%' . $query . '%');
                  });
            })
            ->with(['category', 'reviews'])
            ->paginate(12);

        return view('customer.products.search', compact('products', 'query'));
    }
}
