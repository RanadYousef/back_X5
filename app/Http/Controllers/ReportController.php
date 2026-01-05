<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReportRequest;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ReportController extends Controller
{
    /**
     * Undocumented function
     *display the initial report page
     * @return void
     */
    public function index()
    {
        return view('reports.index');
    }
    public function
        generateReport(
        ReportRequest $request
    ) {
        try {
            $validated = $request->validated();

            $start = $validated['start_date'];
            $end = $validated['end_date'];
            /**
             * book rating report
             */
            $reports = Borrowing::with(['user', 'book'])->whereBetween('created_at', [$start, $end])
                ->get();
            /**
             * top rated books
             */
            $topRatedBooks = Book::orderBy('overall_rating', 'desc')->take(5)->get();
            /**
             * lowest rated books
             */
            $lowestRatedBooks = Book::orderBy('overall_rating', 'asc')->take(5)->get();
            /**
             * average rating
             */
            $avgRating = Book::avg('overall_rating');
            /**
             * most borrowed books
             */
            $mostBorrowed = Book::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])->orderBy('borrowings_count', 'desc')->take(5)->get();
            /**
             * least borrowed books
             */
            $leastBorrowed = Book::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])->orderBy('borrowings_count', 'asc')->take(5)->get();
            /**
             * average borrowing rate
             */
            $avgBorrowingCount = $reports->count() / max(Book::count(), 1);
            /**
             * active borrowings
             */
            $activeBorrowings = Borrowing::whereNull('returned_at')
                ->whereBetween('created_at', [$start, $end])
                ->with(['user', 'book'])
                ->get();
            /**
             * available books
             */
            $availableBooks = Book::whereDoesntHave('borrowings', function ($q) {
                $q->whereNull('returned_at');
            })->get();
            /**
             * top customers (most borrowed)
             */
            $topCustomers = User::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])->orderBy('borrowings_count', 'desc')->take(5)->get();

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
                'topCustomers'
            ));

        } catch (\Exception $e) {
            Log::error('خطأ في التقرير ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحليل البيانات.']);
        }
    }
}