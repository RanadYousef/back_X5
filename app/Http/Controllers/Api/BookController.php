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
                ->withAvg('reviews', 'rating')
                ->withCount('borrows');

            // Search by title
            if (!empty($filters['search'])) {
                $query->where('title', 'LIKE', '%' . $filters['search'] . '%');
            }

            // Filter by category
            if (!empty($filters['category_id'])) {
                $query->where('category_id', $filters['category_id']);
            }

            // Filter by language
            if (!empty($filters['language'])) {
                $query->where('language', $filters['language']);
            }

            // Filter by publish year
            if (!empty($filters['publish_year'])) {
                $query->where('publish_year', $filters['publish_year']);
            }

            // Sorting
            if (!empty($filters['sort'])) {

                switch ($filters['sort']) {

                    case 'rating':
                        $query->orderBy('reviews_avg_rating', 'DESC');
                        break;
            

                    case 'year':
                        $query->orderBy('publish_year', 'DESC');
                        break;

                    case 'title':
                        $query->orderBy('title', 'ASC');
                        break;
                }
            }

            $perPage = $filters['per_page'] ?? 10;
            $books = $query->paginate($perPage);

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
    public function show(Book $book)
    {
        try {
            $book->load(['category']);
            $book->loadAvg('reviews', 'rating');
            $book->loadCount('borrows');

            return $this->success(
                new BookResource($book),
                'Book details loaded successfully'
            );

        } catch (\Exception $e) {

            Log::error("API Book Show Error for Book {$book->id}: " . $e->getMessage());

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
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_avg_rating', 'DESC')
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