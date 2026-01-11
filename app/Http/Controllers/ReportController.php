<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
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
        if (auth()->user()->role !=='admin') {
            abort(403,'For Admin Only');
        }
        return view('reports.index');
    }
    public function
        generateReport(
        ReportRequest $request
    ) { 
        if (auth()->user()->role !=='admin') {
            abort(403,'For Admin Only');
        }
        try {
            $validated = $request->validated();

            $start = $validated['start_date'];
            $end = $validated['end_date'];
            /**
             * book rating report, top and lowest rated books, and average rating
             */
            $reports = Borrowing::with(['user', 'book'])->whereBetween('created_at', [$start, $end])
                ->get();
        
            $topRatedBooks = Book::orderBy('overall_rating', 'desc')->take(5)->get();
            $lowestRatedBooks = Book::orderBy('overall_rating', 'asc')->take(5)->get();
            $avgRating = Book::avg('overall_rating');
            /**
             * most and least borrowed books, and average borrowing rate
             */
            $mostBorrowed = Book::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])->orderBy('borrowings_count', 'desc')->take(5)->get();
            $leastBorrowed = Book::withCount([
                'borrowings' => function ($q) use ($start, $end) {
                    $q->whereBetween('created_at', [$start, $end]);
                }
            ])->orderBy('borrowings_count', 'asc')->take(5)->get();
            $avgBorrowingCount = $reports->count() / max(Book::count(), 1);
            /**
             * active borrowings , and available books
             */
            $activeBorrowings = Borrowing::whereNull('returned_at')
                ->whereBetween('created_at', [$start, $end])
                ->with(['user', 'book'])
                ->get();
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
public function downloadPDF(Request $request)
{
    if (auth()->user()->role !== 'admin') {
        abort(403, 'For Admin Only');
    }

    $query = \App\Models\Borrowing::with(['book', 'user'])
        ->whereBetween('borrowed_at', [$request->start_date, $request->end_date]);

    if ($request->category_id) {
        $query->whereHas('book', function($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });
    }

    $borrowings = $query->get();
    
    $pdf = Pdf::loadView('admin.reports.show', [
        'borrowings' => $borrowings,
        'request' => $request,
        'totalBorrowings' => $borrowings->count(),
    'topCustoers' => collect()
    ]);

    return $pdf->download('Library-Report.pdf');
}
}