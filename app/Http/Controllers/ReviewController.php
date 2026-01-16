<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Services\UserNotificationService;
use Illuminate\Http\RedirectResponse;

class ReviewController extends Controller
{
    /**
     * index all reviews
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
            //update review status
            $review->update([
                'status' => 'approved',
            ]);

            //load relations
            $review->load(['user', 'book']);

            //send notification to user
            UserNotificationService::reviewApproved(
                $review->user,
                $review->book->title
            );

            return back()->with('success', 'Successfully published review');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to publish review');
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

            return back()->with('success', 'Review rejected successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject review');
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

            return back()->with('success', 'Deleted review successfully');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete review');
        }
    }
}
