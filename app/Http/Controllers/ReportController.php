<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Class ReportController
 * * Handles the generation of analytical reports and data visualization 
 * for the library management system.
 */
class ReportController extends Controller
{
    /**
     * Display the initial reports page.
     *
     * @return View
     */
    public function index(): View
    {
        return view('reports.index');
    }

    /**
     * Process and generate analytical library data based on a date range.
     *
     * @param ReportRequest $request
     * @return View|RedirectResponse
     */
    public function generateReport(ReportRequest $request)
    {
        // Validate incoming date range
        $validated = $request->validated();
        $start = $validated['start_date'];
        $end = $validated['end_date'];

        try {
            /** @var \Illuminate\Database\Eloquent\Builder $baseBookQuery */
            $baseBookQuery = Book::query();

            // 1. Calculate Top 5 Rated Books based on reviews average
            $topRatedBooks = (clone $baseBookQuery)
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_avg_rating', 'desc')
                ->take(5)
                ->get();

            // 2. Calculate Top 5 Lowest Rated Books
            $lowestRatedBooks = (clone $baseBookQuery)
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_avg_rating', 'asc')
                ->take(5)
                ->get();

            // 3. Get global average rating for the specified period
            $avgRating = Review::whereBetween('created_at', [$start, $end])
                ->avg('rating') ?: 0;

            // 4. Get Top 5 Most Borrowed Books within the date range
            $mostBorrowed = (clone $baseBookQuery)
                ->withCount([
                    'borrows' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                ])
                ->orderBy('borrows_count', 'desc')
                ->take(5)
                ->get();

            // 5. Get Top 5 Least Borrowed Books within the date range
            $leastBorrowed = (clone $baseBookQuery)
                ->withCount([
                    'borrows' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                ])
                ->orderBy('borrows_count', 'asc')
                ->take(5)
                ->get();

            // 6. Fetch all borrowing transactions for the detailed list
            $reports = Borrowing::with(['user', 'book'])
                ->whereBetween('created_at', [$start, $end])
                ->get();

            // 7. Fetch active borrowings (Books currently out of the library)
            $activeBorrowings = Borrowing::whereNull('returned_at')
                ->whereBetween('created_at', [$start, $end])
                ->with(['user', 'book'])
                ->get();

            // 8. Identify books that are currently available for borrowing
            $availableBooks = Book::whereDoesntHave('borrows', function ($q) {
                $q->whereNull('returned_at');
            })->get();

            // 9. Identify Top 5 Customers by borrowing frequency
            $topCustomers = User::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])
                ->orderBy('borrowings_count', 'desc')
                ->take(5)
                ->get();

            // 10. Calculate average borrowing rate per book
            $avgBorrowingCount = $reports->count() / max(Book::count(), 1);

            // Audit logging
            Log::info("Analytical report generated for period: $start to $end");

            return view('reports.show', compact(
                'reports',
                'topRatedBooks',
                'lowestRatedBooks',
                'avgRating',
                'mostBorrowed',
                'leastBorrowed',
                'avgBorrowingCount',
                'activeBorrowings',
                'availableBooks',
                'topCustomers',
                'start',
                'end'
            ));

        } catch (\Exception $e) {
            // Error handling and logging
            Log::error('Report Generation Failed: ' . $e->getMessage());

            return back()->withErrors([
                'error' => 'An error occurred while analyzing library data.'
            ]);
        }
    }
}