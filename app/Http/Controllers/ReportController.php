<?php

namespace App\Http\Controllers;

use App\Http\Request\ReportRequest;
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
     *عرض صفحة البداية للتقارير
     * @return void
     */
    public function index(){
        return view('reports.index');
    }
    public function
    generateReport(ReportRequest $request)
    {
        try {
            $validated = $request->validated();

        $start = $validated['start_date'];
        $end = $validated['end_date'];
        /**
         * تقرير عن الاستعارات
         */
        $reports =Borrowing::with(['user','book'])->whereBetween('created_at', [$start,$end])
        ->get();
        /**
         * الكتب الأعلى تقييما
         */
        $topRatedBooks = Book::orderBy('overall_rating','desc')->take(5)->get();
        /**
         * الكتب الأقل تقييما
         */
        $lowestRatedBooks =Book::orderBy('overall_rating','asc')->take(5)->get();
    /**
     * متوسط التقييم
     */
        $avgRating = Book::avg('overall_rating');
    /**
     * الكتب الأكثر استعارة
     */
        $mostBorrowed = Book::withCount(['borrowings' => function($q) use ($start , $end){
        $q->whereBetween('created_at',[$start,$end]); }
    ])->orderBy('borrowings_count','desc')->take(5)->get();
    /**
     * الكتب الأقل استعارة
     */
    $leastBorrowed = Book::withCount(['borrowings'=>function($q) use ($start ,$end) {
        $q->whereBetween('created_at', [$start,$end]);
    }
    ])->orderBy('borrowings_count','asc')->take(5)->get();
    /**
     * متوسط الاستعارة
     */
    $avgBorrowingCount = $reports->count() / max(Book::count(),1);
    /**
     * الاستعارات النشطة
     */
    $activeBorrowings = Borrowing::whereNull('returned_at')
                ->whereBetween('created_at', [$start, $end])
                ->with(['user', 'book'])
                ->get();
            /**
             *  * الكتب المتاحة
             */
$availableBooks = Book::whereDoesntHave('borrowings', function($q) {
                $q->whereNull('returned_at');
            })->get();
            /**
 * أفضل الزبائن الأكثر استعارة        
             */
            $topCustomers = User::withCount(['borrowings' => function($q) use ($start, $end) {
                $q->whereBetween('created_at', [$start, $end]);
            }])->orderBy('borrowings_count', 'desc')->take(5)->get();

            return view('reports.show', compact(
                'reports', 'topRatedBooks', 'lowestRatedBooks', 'avgRating',
                'mostBorrowed', 'leastBorrowed', 'avgBorrowingCount',
                'activeBorrowings', 'availableBooks', 'topCustomers'
            ));

        } catch (\Exception $e) {
            Log::error('خطأ في التقرير ' . $e->getMessage());
            return back()->withErrors(['error' =>'حدث خطأ أثناء تحليل البيانات.']);
        }
    }
}