<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\BookFilterRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

class BookController extends BaseApiController
{
    /**
     * Display a paginated list of books with filters & sorting.
     */
    public function index(BookFilterRequest $request)
    {
        try {
            $filters = $request->validated();

            $query = Book::with('category')
                ->withAvg('reviews', 'stars')
                ->withCount('borrows');

            // Search by title
            if (!empty($filters['search'])) {
                $query->where('title', 'LIKE', '%' . $filters['search'] . '%');
            }

            // Filter by category
            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            $books = $query->paginate(10);

            return $this->success(
                BookResource::collection($books),
                'Books retrieved successfully'
            );

        } catch (\Exception $e) {

            Log::error('API Books Index Error: ' . $e->getMessage());

            return $this->error(
                'Failed to fetch books',
                500
            );
        }
    }

    /**
     * Display a single book details.
     */
    public function show($id)
    {
        try {
            $book = Book::with('category')
                ->withAvg('reviews', 'stars')
                ->withCount('borrows')
                ->findOrFail($id);

            return $this->success(
                new BookResource($book),
                'Book details loaded successfully'
            );

        } catch (\Exception $e) {

            Log::error("API Book Show Error for ID $id: " . $e->getMessage());

            return $this->error(
                'Book not found',
                404
            );
        }
    }

    /**
     * Get most borrowed books (top suggestions).
     */
    public function mostBorrowed()
    {
        try {
            $books = Book::with('category')
                ->withCount('borrows')
                ->orderBy('borrows_count', 'DESC')
                ->take(10)
                ->get();

            return $this->success(
                BookResource::collection($books),
                'Top borrowed books retrieved'
            );

        } catch (\Exception $e) {

            Log::error("API Most Borrowed Error: " . $e->getMessage());

            return $this->error(
                'Failed to load suggestions',
                500
            );
        }
    }

    /**
     * Get top-rated books based on average review stars.
     */
    public function topRated()
    {
        try {
            $books = Book::with('category')
                ->withAvg('reviews', 'stars')
                ->orderBy('reviews_avg_stars', 'DESC')
                ->take(10)
                ->get();

            return $this->success(
                BookResource::collection($books),
                'Top rated books retrieved'
            );

        } catch (\Exception $e) {

            Log::error("API Top Rated Error: " . $e->getMessage());

            return $this->error(
                'Failed to load top rated books',
                500
            );
        }
    }
}