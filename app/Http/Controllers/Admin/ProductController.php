<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Exception;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'creator'])
            ->latest()
            ->get();

        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'main_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_image' => 'nullable|string',
            'gallery_images_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|string',
            'specifications' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['in_stock'] = $validated['stock_quantity'] > 0;
        $validated['created_by'] = Auth::id();

        // Handle main image upload
        if ($request->hasFile('main_image_file')) {
            $mainImage = $request->file('main_image_file');
            $mainImageName = time() . '_main_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
            $mainImage->move(public_path('uploads/products'), $mainImageName);
            $validated['main_image'] = '/uploads/products/' . $mainImageName;
        }

        // Handle gallery images upload
        $galleryImages = [];
        if ($request->hasFile('gallery_images_files')) {
            foreach ($request->file('gallery_images_files') as $image) {
                $imageName = time() . '_gallery_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $galleryImages[] = '/uploads/products/' . $imageName;
            }
        }

        // Handle gallery image URLs
        if ($request->gallery_images) {
            $urlImages = array_filter(array_map('trim', explode("\n", $request->gallery_images)));
            $galleryImages = array_merge($galleryImages, $urlImages);
        }

        $validated['gallery_images'] = $galleryImages;

        // Handle specifications JSON
        if ($request->specifications) {
            try {
                $validated['specifications'] = json_decode($request->specifications, true) ?: [];
            } catch (Exception $e) {
                $validated['specifications'] = [];
            }
        } else {
            $validated['specifications'] = [];
        }

        // Remove file upload fields that shouldn't be stored in DB
        unset($validated['main_image_file'], $validated['gallery_images_files']);

        try {
            Product::create($validated);
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully!');
    }

    public function show(Product $product)
    {
        $product->load(['category', 'creator', 'reviews.user', 'orderItems.order']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'short_description' => 'nullable|string|max:500',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'main_image_file' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'main_image' => 'nullable|string',
            'gallery_images_files.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery_images' => 'nullable|string',
            'specifications' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);
        $validated['in_stock'] = $validated['stock_quantity'] > 0;

        // Handle main image upload
        if ($request->hasFile('main_image_file')) {
            // Delete old main image if it exists and is a local file
            if ($product->main_image && str_starts_with($product->main_image, '/uploads/')) {
                $oldImagePath = public_path($product->main_image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            $mainImage = $request->file('main_image_file');
            $mainImageName = time() . '_main_' . uniqid() . '.' . $mainImage->getClientOriginalExtension();
            $mainImage->move(public_path('uploads/products'), $mainImageName);
            $validated['main_image'] = '/uploads/products/' . $mainImageName;
        }

        // Handle gallery images upload
        $galleryImages = $product->gallery_images ?: [];
        if ($request->hasFile('gallery_images_files')) {
            foreach ($request->file('gallery_images_files') as $image) {
                $imageName = time() . '_gallery_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('uploads/products'), $imageName);
                $galleryImages[] = '/uploads/products/' . $imageName;
            }
        }

        // Handle gallery image URLs
        if ($request->gallery_images) {
            $urlImages = array_filter(array_map('trim', explode("\n", $request->gallery_images)));
            $galleryImages = array_merge($galleryImages, $urlImages);
        }

        $validated['gallery_images'] = array_unique($galleryImages);

        // Handle specifications JSON
        if ($request->specifications) {
            try {
                $validated['specifications'] = json_decode($request->specifications, true) ?: [];
            } catch (Exception $e) {
                $validated['specifications'] = $product->specifications ?: [];
            }
        } else {
            $validated['specifications'] = $product->specifications ?: [];
        }

        // Remove file upload fields that shouldn't be stored in DB
        unset($validated['main_image_file'], $validated['gallery_images_files']);

        $product->update($validated);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }

    public function toggleStatus(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);

        $status = $product->is_active ? 'activated' : 'deactivated';
        return redirect()->back()->with('success', "Product {$status} successfully!");
    }

    public function toggleFeatured(Product $product)
    {
        $product->update(['is_featured' => !$product->is_featured]);

        $status = $product->is_featured ? 'marked as featured' : 'unmarked as featured';
        return redirect()->back()->with('success', "Product {$status} successfully!");
    }
}
