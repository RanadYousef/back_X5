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
    
        return view('reports.index');
    }
    public function
        generateReport(
        ReportRequest $request
    ) { 
        $validated = $request->validated();
        $start = $validated['start_date'];
        $end = $validated['end_date'];
        try {
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
            Log::error('خطأ في التقرير ' . $e->getMessage());
            return back()->withErrors(['error' => 'حدث خطأ أثناء تحليل البيانات.']);
        }
    }
public function downloadPDF(ReportRequest $request)
{
    $validated = $request->validated();
    try {

    $query = Borrowing::with(['book', 'user'])
        ->whereBetween('created_at', [$validated['start_date'],$validated['end_date']]);

    if ($request->category_id) {
        $query->whereHas('book', function($q) use ($request) {
            $q->where('category_id', $request->category_id);
        });
    }

    $borrowings = $query->get();
    Log::info("Admin downloaded PDF report");
    
    $pdf = Pdf::loadView('reports.show', [
        'borrowings' => $borrowings,
        'request' => $request,
        'totalBorrowings' => $borrowings->count(),
    'topCustoers' => collect()
    ]);

    return $pdf->download('Library-Report.pdf');
} catch (Exception $e) {
    Log::error('PDF Export Error: ' . $e->getMessage());
    return back()->withErrors(['error' => 'فشل توليد ملف PDF.']);
}
}
}