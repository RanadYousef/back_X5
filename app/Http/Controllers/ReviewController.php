<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\StoreReviewRequest;
use App\Http\Requests\UpdateReviewRequest;

class ReviewController extends Controller
{
    public function __construct()
    {
        // Allow guests to view lists and details; require authentication for other actions
        $this->middleware('auth')->except(['index', 'show']);
    }

    /**
     * Display a paginated list of reviews with optional filtering.
     */
    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);

        $query = Review::with(['user', 'book'])->orderBy('created_at', 'desc');

        if ($request->filled('book_id')) {
            $query->where('book_id', $request->book_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $reviews = $query->paginate($perPage);

        return response()->json($reviews, Response::HTTP_OK);
    }

    /**
     * Show a single review with its relations.
     */
    public function show(Review $review)
    {
        $review->load(['user', 'book']);
        return response()->json($review, Response::HTTP_OK);
    }

    /**
     * Store a new review.
     *
     * Uses StoreReviewRequest for validation and authorization.
     * Prevents duplicate reviews by the same user for the same book.
     */
    public function store(StoreReviewRequest $request)
    {
        try {
            $data = $request->validated();
            $userId = $request->user()->id;

            // Prevent duplicate review by the same user for the same book
            $exists = Review::where('user_id', $userId)
                            ->where('book_id', $data['book_id'])
                            ->exists();

            if ($exists) {
                return response()->json([
                    'message' => 'You already reviewed this book.'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $data['user_id'] = $userId;

            $review = Review::create($data);
            $review->load(['user', 'book']);

            return response()->json($review, Response::HTTP_CREATED);
        } catch (QueryException $e) {
            Log::error('Review store DB error: '.$e->getMessage());
            return response()->json([
                'message' => 'Database error while creating review.'
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Throwable $e) {
            Log::error('Review store error: '.$e->getMessage());
            return response()->json([
                'message' => 'Something went wrong while creating review.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update an existing review.
     *
     * Uses UpdateReviewRequest for validation and authorization (owner or 'manage reviews').
     */
    public function update(UpdateReviewRequest $request, Review $review)
    {
        try {
            $review->update($request->validated());
            $review->load(['user', 'book']);

            return response()->json($review, Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Review update error: '.$e->getMessage());
            return response()->json([
                'message' => 'Update failed'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Soft delete a review.
     *
     * Only the review owner or a user with the 'manage reviews' permission can delete.
     */
    public function destroy(Request $request, Review $review)
    {
        $user = $request->user();

        if ($review->user_id !== $user->id && ! $user->can('manage reviews')) {
            return response()->json(['message' => 'Forbidden'], Response::HTTP_FORBIDDEN);
        }

        try {
            $review->delete();
            return response()->json(['message' => 'Deleted'], Response::HTTP_OK);
        } catch (\Throwable $e) {
            Log::error('Review delete error: '.$e->getMessage());
            return response()->json(['message' => 'Delete failed'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
