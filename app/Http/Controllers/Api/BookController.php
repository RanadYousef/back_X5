<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Requests\Api\BookFilterRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Support\Facades\Log;

/**
 * Class BookController
 *
 * Handles all public API operations related to books.
 * Provides endpoints for listing books with filters,
 * viewing single book details, and retrieving book suggestions
 * such as most borrowed and top rated books.
 */
class BookController extends BaseApiController
{
    /**
     * Retrieve a paginated list of books with filtering and sorting.
     *
     * Supports filtering by:
     * - title (search)
     * - category
     * - language
     * - publish year
     *
     * Supports sorting by:
     * - average rating
     * - publish year
     * - title
     *
     * Only approved reviews are used to calculate average rating
     * and ratings count.
     *
     * @param BookFilterRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(BookFilterRequest $request)
    {
        try {
            $filters = $request->validated();

            $query = Book::with('category')

                ->withCount('borrows');

            $query
                ->withAvg(['reviews as average_rating' => function ($q) {
                    $q->where('status', 'approved');
                }], 'rating');

            $query
                ->withCount(['reviews as ratings_count' => function ($q) {
                    $q->where('status', 'approved');
                }]);
                ->withAvg([
                    'reviews as average_rating' => function ($q) {
                        $q->where('status', 'approved');
                    }
                ], 'rating');

            $query
                ->withCount([
                    'reviews as ratings_count' => function ($q) {
                        $q->where('status', 'approved');
                    }
                ]);

            $query
                ->when($filters['search'] ?? null, function ($q, $search) {
                    $q->where('title', 'LIKE', "%{$search}%");
                })

                ->when($filters['category_id'] ?? null, function ($q, $categoryId) {
                    $q->where('category_id', $categoryId);
                })

                ->when($filters['language'] ?? null, function ($q, $language) {
                    $q->where('language', $language);
                })

                ->when($filters['publish_year'] ?? null, function ($q, $year) {
                    $q->where('publish_year', $year);
                })

                ->when($filters['sort'] ?? null, function ($q, $sort) {
                    match ($sort) {
                        'rating' => $q->orderBy('average_rating', 'DESC'),
                        'year'   => $q->orderBy('publish_year', 'DESC'),
                        'title'  => $q->orderBy('title', 'ASC'),
                        default  => null,
                    };
                });

            $perPage = $filters['per_page'] ?? 10;
            $books = $query->paginate($perPage);
            $message = $books->isEmpty()
                ? 'No books match the filter.'
                : 'Books retrieved successfully';

            return $this->success(
                BookResource::collection($books),
                $message
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
     * Retrieve details of a single book by its ID.
     *
     * Loads the book category and borrow count.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $book = Book::with('category')
                ->withCount('borrows')
                ->findOrFail($id);
            $book->loadAvg('reviews', 'rating');
            $suggestions = Book::where('category_id', $book->category_id)
                ->where('id', '!=', $book->id)
                ->withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->take(4)
                ->get();

            return $this->success(
                [
                    'book' => new BookResource($book),
                    'suggestions' => BookResource::collection($suggestions),
                ],
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
     * Retrieve the most borrowed books.
     *
     * Returns a list of books ordered by borrow count
     * in descending order.
     *
     * @return \Illuminate\Http\JsonResponse
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
     * Retrieve top rated books based on approved reviews.
     *
     * Calculates average rating and ratings count
     * using only approved reviews.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function topRated()
    {
        try {
            $books = Book::with('category')
                ->withAvg(
                    ['reviews as average_rating' => function ($q) {
                        $q->where('status', 'approved');
                    }],
                    'rating'
                )

                ->withCount(['reviews as ratings_count' => function ($q) {
                    $q->where('status', 'approved');
                }])
                    [
                        'reviews as average_rating' => function ($q) {
                            $q->where('status', 'approved');
                        }
                    ],
                    'rating'
                )

                ->withCount([
                    'reviews as ratings_count' => function ($q) {
                        $q->where('status', 'approved');
                    }
                ])
                ->orderByDesc('average_rating')
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
