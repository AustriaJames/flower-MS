<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        // Debug: Log cart items for debugging
        Log::info('Cart items for user ' . Auth::id() . ': ' . $cartItems->count() . ' items found');
        foreach ($cartItems as $item) {
            Log::info('Cart item ID: ' . $item->id . ', Product: ' . $item->product->name . ', Quantity: ' . $item->id);
        }

        return view('customer.cart.index', compact('cartItems', 'total'));
    }

    public function add(Request $request)
    {
        Log::info('Cart add method called with data: ' . json_encode($request->all()));

        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
                'add_ons' => 'array',
                'personal_message' => 'nullable|string|max:500'
            ]);

            Log::info('Validation passed, finding product...');
            $product = Product::findOrFail($request->product_id);
            Log::info('Product found: ' . $product->name . ' with price: ' . $product->price);

                        // Check if product is already in cart
            Log::info('Checking for existing cart item...');
            $existingItem = CartItem::where('user_id', Auth::id())
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingItem) {
                Log::info('Existing cart item found, updating quantity...');
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $request->quantity
                ]);
                Log::info('Quantity updated successfully');
            } else {
                Log::info('No existing cart item, creating new one...');
                $cartItem = CartItem::create([
                    'user_id' => Auth::id(),
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'add_ons' => null,
                    'personal_message' => $request->personal_message,
                    'price' => $product->price
                ]);
                Log::info('New cart item created with ID: ' . $cartItem->id);
            }

                                if ($request->expectsJson()) {
                Log::info('Returning JSON response for cart add');
                return response()->json(['success' => true, 'message' => 'Product added to cart successfully!'])
                    ->header('Content-Type', 'application/json');
            }

        return redirect()->back()->with('success', 'Product added to cart successfully!');
        } catch (\Exception $e) {
            Log::error('Cart add error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error adding to cart: ' . $e->getMessage()], 500)
                    ->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('error', 'Error adding to cart: ' . $e->getMessage());
        }
    }

    public function update(Request $request, $cartItemId)
    {
        Log::info('Cart update method called for cart item ID: ' . $cartItemId);

        try {
            // Find the cart item manually to handle not found cases
            $cartItem = CartItem::where('id', $cartItemId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$cartItem) {
                Log::error('Cart item not found: ' . $cartItemId);
                if ($request->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cart item not found'], 404)
                        ->header('Content-Type', 'application/json');
                }
                return redirect()->back()->with('error', 'Cart item not found');
            }

            $request->validate([
                'quantity' => 'required|integer|min:1',
                'add_ons' => 'array',
                'personal_message' => 'nullable|string|max:500'
            ]);

            $cartItem->update([
                'quantity' => $request->quantity,
                'add_ons' => null,
                'personal_message' => $request->personal_message
            ]);

            if ($request->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Cart updated successfully!'])
                    ->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('success', 'Cart updated successfully!');
        } catch (\Exception $e) {
            Log::error('Cart update error: ' . $e->getMessage());

            if ($request->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error updating cart: ' . $e->getMessage()], 500)
                    ->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('error', 'Error updating cart: ' . $e->getMessage());
        }
    }

    public function remove($cartItemId)
    {
        try {
            // Find the cart item manually to handle not found cases
            $cartItem = CartItem::where('id', $cartItemId)
                ->where('user_id', Auth::id())
                ->first();

            if (!$cartItem) {
                Log::error('Cart item not found for removal: ' . $cartItemId);
                if (request()->expectsJson()) {
                    return response()->json(['success' => false, 'message' => 'Cart item not found'], 404)
                        ->header('Content-Type', 'application/json');
                }
                return redirect()->back()->with('error', 'Cart item not found');
            }

            $cartItem->delete();

            if (request()->expectsJson()) {
                return response()->json(['success' => true, 'message' => 'Item removed from cart!'])
                    ->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('success', 'Item removed from cart!');
        } catch (\Exception $e) {
            Log::error('Cart remove error: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Error removing item: ' . $e->getMessage()], 500)
                    ->header('Content-Type', 'application/json');
            }

            return redirect()->back()->with('error', 'Error removing item: ' . $e->getMessage());
        }
    }
}
