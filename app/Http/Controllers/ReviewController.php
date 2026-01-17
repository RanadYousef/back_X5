<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     *index all reviews
     * (admin | employee)
     */
    public function index()
    {
        $reviews = Review::with(['user', 'book'])
            ->latest()
            ->get();

        return view('reviews.index', compact('reviews'));
    }

    /**
     * approve review
     * (employee only)
     */
    public function approve(Review $review): RedirectResponse
    {
        try {
            $review->update([
                'status' => 'approved',
            ]);

            return back()->with('success', 'sucessfully published review');
        } catch (\Exception $e) {
            return back()->with('error', 'failed to publish review');
        }
    }

    /**
     * reject review
     * (employee only)
     */
    public function reject(Review $review): RedirectResponse
    {
        try {
            $review->update([
                'status' => 'rejected',
            ]);

            return back()->with('success', 'review rejected successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'failed to reject review');
        }
    }

    /**
     * delete review
     * (employee only)
     */
    public function destroy(Review $review): RedirectResponse
    {
        try {
            $review->delete();

            return back()->with('success', 'deleted review successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'failed to delete review');
        }
    }
}
