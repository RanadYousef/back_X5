<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\Book;
use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\StoreReviewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;


class ReviewController extends BaseApiController
{
    /**
     * Undocumented function
     *display reviews
     * @return void
     */
    public function myReviews()
    {
        try {
            $reviews = Review::with('book')
                ->where('user_id', auth()->id())
                ->latest()
                ->get();

            return $this->success($reviews, 'your reviews retrieved successfully');
        } catch (\Exception $e) {
            Log::error('Error fetching reviews: ' . $e->getMessage(), ['user_id' => auth()->id]);
            return $this->error('Failed to retrieve reviews', 500);
        }
    }

    /**
     * Undocumented function
     *submit a new book review (rating and comment)
     * @param StoreReviewRequest $request
     * @return void
     */
    public function store(StoreReviewRequest $request)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {

            $review = Review::create([
                'user_id' => auth()->id(),
                'rating' => $validated['rating'],
                'status' => 'pending',
                'comment' => $validated['comment'],
                'book_id' => $validated['book_id'],
            ]);

            DB::commit();
            return $this->success($review, 'Review added successfully', 201);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error storing review: ' . $e->getMessage());
            return $this->error('Failed to add review', 500, );
        }
    }

    /**
     * Delete a review (only if it belongs to the authenticated user)
     *
     * @param Request $request
     * @param int $reviewId
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Request $request, $reviewId)
    {
        try {
            $review = Review::findOrFail($reviewId);

            // Check if the review belongs to the authenticated user
            if ($review->user_id !== auth()->id()) {
                return $this->error('You are not authorized to delete this review', 403);
            }

            DB::beginTransaction();

            $review->delete();

            DB::commit();

            return $this->success(null, 'Review deleted successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->error('Review not found', 404);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting review: ' . $e->getMessage(), ['user_id' => auth()->id(), 'review_id' => $reviewId]);
            return $this->error('Failed to delete review', 500);
        }
    }
}