<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Requests\StoreReviewRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
   /**
    * Undocumented function
    *display reviews
    * @return void
    */
    public function myReviews()
    {
        $reviews = Review::with('book')
            ->where('user_id', auth()->id())
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'Reviews retrieved successfully',
            'data' => $reviews
        ], 200);
    }

    /**
     * Undocumented function
     *submit a new book review (rating and comment)
     * @param StoreReviewRequest $request
     * @return void
     */
    public function store(StoreReviewRequest $request)
    {
        DB::beginTransaction(); 
        try {
            $review = Review::create([
                'user_id' => auth()->id(),
                'book_id' => $request->book_id,
                'rating'  => $request->rating, 
                'comment' => $request->comment, 
            ]);

            DB::commit();
            return response()->json([
                'status' => true,
                'message' => 'Review added successfully',
                'data' => $review
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => 'Something went wrong!',
                'error' => $e->getMessage()
            ], 500);
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
            $review = Review::where('id', $id)
                ->where('user_id', auth()->id()) 
                ->firstOrFail();

            $review->delete();

            return response()->json([
                'status' => true,
                'message' => 'Review deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Review not found or unauthorized'
            ], 404);
        }
    }

}