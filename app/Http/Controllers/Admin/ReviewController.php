<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        $reviews = $query->latest()->get();

        return view('admin.reviews.index', compact('reviews'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'product']);
        return view('admin.reviews.show', compact('review'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        $statuses = ['pending', 'approved', 'rejected'];
        return view('admin.reviews.edit', compact('review', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $request->validate([
            'status' => 'required|string|in:pending,approved,rejected',
            'admin_response' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $review->status;

        $review->update([
            'status' => $request->status,
            'admin_response' => $request->admin_response,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        // Send notification to customer if status changed
        if ($oldStatus !== $request->status) {
            $this->sendStatusNotification($review);
        }

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Approve review
     */
    public function approve(Review $review)
    {
        $review->update([
            'status' => 'approved',
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Review approved successfully.');
    }

    /**
     * Reject review
     */
    public function reject(Request $request, Review $review)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        $review->update([
            'status' => 'rejected',
            'admin_notes' => $request->rejection_reason,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Review rejected successfully.');
    }

    /**
     * Reply to review
     */
    public function reply(Request $request, Review $review)
    {
        $request->validate([
            'admin_response' => 'required|string|max:1000',
        ]);

        $review->update([
            'admin_response' => $request->admin_response,
            'replied_at' => now(),
            'replied_by' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Reply added successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();
        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }

    /**
     * Bulk actions on reviews
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|string|in:approve,reject,delete',
            'reviews' => 'required|array',
            'reviews.*' => 'exists:reviews,id',
        ]);

        $reviews = Review::whereIn('id', $request->reviews)->get();

        switch ($request->action) {
            case 'approve':
                $reviews->each(function ($review) {
                    $review->update([
                        'status' => 'approved',
                        'reviewed_at' => now(),
                        'reviewed_by' => Auth::id(),
                    ]);
                });
                $message = 'Reviews approved successfully.';
                break;

            case 'reject':
                $reviews->each(function ($review) {
                    $review->update([
                        'status' => 'rejected',
                        'reviewed_at' => now(),
                        'reviewed_by' => Auth::id(),
                    ]);
                });
                $message = 'Reviews rejected successfully.';
                break;

            case 'delete':
                $reviews->each(function ($review) {
                    $review->delete();
                });
                $message = 'Reviews deleted successfully.';
                break;
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Export reviews
     */
    public function export(Request $request)
    {
        $query = Review::with(['user', 'product']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('rating')) {
            $query->where('rating', $request->rating);
        }

        $reviews = $query->get();

        $filename = 'reviews_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reviews) {
            $file = fopen('php://output', 'w');

            // Add headers
            fputcsv($file, [
                'ID', 'Customer', 'Product', 'Rating', 'Comment', 'Status',
                'Admin Response', 'Created At', 'Reviewed At'
            ]);

            // Add data
            foreach ($reviews as $review) {
                fputcsv($file, [
                    $review->id,
                    $review->user->name ?? 'Guest',
                    $review->product->name ?? 'N/A',
                    $review->rating,
                    $review->comment,
                    $review->status,
                    $review->admin_response,
                    $review->created_at->format('Y-m-d H:i:s'),
                    $review->reviewed_at ? $review->reviewed_at->format('Y-m-d H:i:s') : 'N/A',
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Send status notification to customer
     */
    private function sendStatusNotification($review)
    {
        // This would typically send an email notification to the customer
        // about their review status change
        // For now, we'll just log it
        Log::info("Review {$review->id} status changed to {$review->status}");
    }
}
