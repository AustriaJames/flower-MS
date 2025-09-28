<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class HomeController extends Controller
{
    /**
     * Show the application homepage.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $featuredProducts = Product::where('is_featured', true)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with(['category', 'reviews'])
            ->take(8)
            ->get();

        $flowerOfTheWeek = Product::where('is_flower_of_week', true)
            ->where('is_active', true)
            ->where('in_stock', true)
            ->with(['category', 'reviews'])
            ->first();

        $categories = Category::where('is_occasion', false)
            ->take(6)
            ->get();

        $testimonials = Review::with(['user', 'product'])
            ->where('rating', '>=', 4)
            ->take(3)
            ->get();

        return view('customer.home', compact('featuredProducts', 'flowerOfTheWeek', 'categories', 'testimonials'));
    }

    /**
     * Show the user profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile()
    {
        $user = Auth::user();
                    $orders = $user->orders()->with(['orderItems.product'])->latest()->take(5)->get();
        $bookings = $user->bookings()->with(['category'])->latest()->take(5)->get();

        return view('customer.profile.index', compact('user', 'orders', 'bookings'));
    }

    /**
     * Update the user profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateProfile(Request $request)
    {
        try {
            $request->validate([
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
            ]);

            $user = Auth::user();

            // Log the current user data before update
            Log::info('Profile update - Before update', [
                'user_id' => $user->id,
                'current_data' => [
                    'first_name' => $user->first_name,
                    'middle_name' => $user->middle_name,
                    'last_name' => $user->last_name,
                    'phone' => $user->phone,
                ],
                'request_data' => $request->only(['first_name', 'middle_name', 'last_name', 'phone'])
            ]);

            // Update the user with the validated data
            $updated = $user->update([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
            ]);

            // Log the result
            Log::info('Profile update - After update', [
                'user_id' => $user->id,
                'update_successful' => $updated,
                'updated_data' => [
                    'first_name' => $user->fresh()->first_name,
                    'middle_name' => $user->fresh()->middle_name,
                    'last_name' => $user->fresh()->last_name,
                    'phone' => $user->fresh()->phone,
                ]
            ]);

            if ($updated) {
                return redirect()->back()->with('success', 'Profile updated successfully!');
            } else {
                return redirect()->back()->with('error', 'Failed to update profile. Please try again.');
            }

        } catch (\Exception $e) {
            Log::error('Profile update failed', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'An error occurred while updating your profile. Please try again.');
        }
    }
}
