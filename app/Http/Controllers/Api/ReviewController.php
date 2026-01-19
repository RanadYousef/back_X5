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
    public function store(StoreReviewRequest $request, $id)
    {
        $validated = $request->validated();
        DB::beginTransaction();
        try {
           $book = Book::find($id);
           if (!$book) {
            return $this->error('not found',404);
           }
            $review = Review::create([
                'user_id' => auth()->id(),
                'book_id' => $id,
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
     * Undocumented function
     *delete a review
     * @param [type] $id
     * @return void
     */
    public function destroy($id)
    {
        try {
            $review = Review::withTrashed()->find($id);
            if (!$review){
                return $this->error('التقييم غير موجود',404);
            }
            if ($review->user_id !== auth()->id()) {
                return $this->error('Unauthorized to delete this review', 403);
            }

            $review->delete();

            return $this->success(null, 'Review deleted successfully');

        } catch (\Exception $e) {
            Log::error('Error deleting review ID ' . $review->id . ': ' . $e->getMessage());
            return $this->error('Failed to delete review', 500);
        }
    }

}