<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Store a newly created review.
     */
    public function store(Request $request)
    {

        $request->validate([
            'product_id' => 'required|exists:products,id',
            'order_id' => 'required|exists:orders,id',
            'order_item_id' => 'required|exists:order_items,id',
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000'
        ]);

        // Allow multiple reviews per product as long as from different orders/order items
        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
            'order_id' => $request->order_id,
            'order_item_id' => $request->order_item_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending'
        ]);

        // Update product average rating
        $this->updateProductRating($request->product_id);

        return redirect()->back()->with('success', 'Review submitted successfully! It will be visible after admin approval.');
    }

    /**
     * Update the specified review.
     */
    public function update(Request $request, Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        // Update product average rating
        $this->updateProductRating($review->product_id);

        return redirect()->back()->with('success', 'Review updated successfully!');
    }

    /**
     * Remove the specified review.
     */
    public function destroy(Review $review)
    {
        if ($review->user_id !== Auth::id()) {
            abort(403);
        }

        $productId = $review->product_id;
        $review->delete();

        // Update product average rating
        $this->updateProductRating($productId);

        return redirect()->back()->with('success', 'Review deleted successfully!');
    }

    /**
     * Update product average rating and review count.
     */
    private function updateProductRating($productId)
    {
        // Consider all reviews (including pending) so product rating reflects recent submissions
        $reviews = Review::where('product_id', $productId);
        $avgRating = $reviews->avg('rating');
        $reviewCount = $reviews->count();
        
        Product::where('id', $productId)->update([
            'rating' => $avgRating ? round($avgRating, 1) : 0,
            'review_count' => $reviewCount
        ]);
    }
}
