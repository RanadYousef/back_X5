<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use App\Models\Review;
use App\Models\Category;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * Class ReportController
 * Handles the generation of analytical reports for the library system.
 */
class ReportController extends Controller
{
    public function index()
    {
        return view('reports.index');
    }
    public function generateReport(ReportRequest $request)
    {
        $validated = $request->validated();
        $start = $validated['start_date'];
        $end = $validated['end_date'];

        try {
            /** @var \Illuminate\Database\Eloquent\Builder $baseBookQuery */
            $baseBookQuery = Book::query();

            // Calculate Top Rated Books using dynamic average rating (Virtual Field)
            $topRatedBooks = (clone $baseBookQuery)
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_avg_rating', 'desc')
                ->take(5)->get();

            // Calculate Lowest Rated Books using dynamic average rating
            $lowestRatedBooks = (clone $baseBookQuery)
                ->withAvg('reviews', 'rating')
                ->orderBy('reviews_avg_rating', 'asc')
                ->take(5)->get();

            // Get global average rating for the specified period
            $avgRating = Review::whereBetween('created_at', [$start, $end])->avg('rating') ?: 0;

            // Get Most Borrowed Books based on the "borrows" relationship count
            $mostBorrowed = (clone $baseBookQuery)
                ->withCount([
                    'borrows' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                ])
                ->orderBy('borrows_count', 'desc')
                ->take(5)->get();

            // Get Least Borrowed Books
            $leastBorrowed = (clone $baseBookQuery)
                ->withCount([
                    'borrows' => function ($q) use ($start, $end) {
                        $q->whereBetween('created_at', [$start, $end]);
                    }
                ])
                ->orderBy('borrows_count', 'asc')
                ->take(5)->get();

            // Fetch all borrowing transactions within the date range
            $reports = Borrowing::with(['user', 'book'])
                ->whereBetween('created_at', [$start, $end])->get();

            // Fetch currently active borrowings (Books not yet returned)
            $activeBorrowings = Borrowing::whereNull('returned_at')
                ->whereBetween('created_at', [$start, $end])
                ->with(['user', 'book'])->get();

            // Identify books that are currently available in the library
            $availableBooks = Book::whereDoesntHave('borrows', function ($q) {
                $q->whereNull('returned_at');
            })->get();

            // Identify top customers based on their total borrowing count
            $topCustomers = User::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])->orderBy('borrowings_count', 'desc')->take(5)->get();

            // Calculate average borrowing rate per book
            $avgBorrowingCount = $reports->count() / max(Book::count(), 1);

            // Log activity for auditing purposes
            Log::info("Admin generated a report from $start to $end");

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
            // Handle any unexpected errors during report generation
            Log::error('Report Error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحليل البيانات.']);
        }
    }
    public function exportPDF(ReportRequest $request)
    {
        $start = $request->query('start_date');
        $end = $request->query('end_date');
        $baseBookQuery = Book::query();

        $topRatedBooks = (clone $baseBookQuery)->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')->take(5)->get();

        $lowestRatedBooks = (clone $baseBookQuery)->withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'asc')->take(5)->get();

        $avgRating = Review::whereBetween('created_at', [$start, $end])->avg('rating') ?: 0;

        $mostBorrowed = (clone $baseBookQuery)->withCount([
            'borrows' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }
        ])->orderBy('borrows_count', 'desc')->take(5)->get();

        $leastBorrowed = (clone $baseBookQuery)->withCount([
            'borrows' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }
        ])->orderBy('borrows_count', 'asc')->take(5)->get();

        $reports = Borrowing::with(['user', 'book'])
            ->whereBetween('created_at', [$start, $end])->get();

        $activeBorrowings = Borrowing::whereNull('returned_at')
            ->whereBetween('created_at', [$start, $end])->with(['user', 'book'])->get();

        $avgBorrowingCount = $reports->count() / max(Book::count(), 1);

        $availableBooks = Book::whereDoesntHave('borrows', function ($q) {
            $q->whereNull('returned_at');
        })->get();

        $topCustomers = User::withCount([
            'borrowings' => function ($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }
        ])->orderBy('borrowings_count', 'desc')->take(5)->get();

        $pdf = Pdf::loadView('reports.pdf', compact(
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

        return $pdf->download('full-library-report.pdf');
    }
}