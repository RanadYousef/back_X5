<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Http\Requests\StoreBorrowingRequest;

/**
 * كنترولر إدارة الاستعارات
 */
class BorrowingController extends Controller
{
    /**
     * عرض جميع عمليات الاستعارة
     */
    public function index()
    {
        $borrowings = Borrowing::with(['book', 'user'])->get();
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * عرض نموذج الاستعارة
     */
    public function create()
    {
        return view('borrowings.create');
    }

    /**
     * تنفيذ عملية استعارة كتاب
     */
    public function store(StoreBorrowingRequest $request)
    {
        try {
            // جلب الكتاب
            $book = Book::findOrFail($request->book_id);

            // التحقق من توفر نسخ
            if ($book->quantity < 1) {
                return back()->with('error', 'الكتاب غير متوفر');
            }

            // إنشاء سجل الاستعارة
            Borrowing::create([
                'book_id' => $book->id,
                'user_id' => auth()->id(),
                'status'  => 'borrowed',
            ]);

            // إنقاص عدد النسخ
            $book->decrement('quantity');

            return redirect()->route('borrowings.index')
                ->with('success', 'تمت استعارة الكتاب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء الاستعارة');
        }
    }

    /**
     * إرجاع كتاب مستعار
     */
    public function returnBook(Borrowing $borrowing)
    {
        try {
            if ($borrowing->status === 'returned') {
                return back()->with('error', 'الكتاب مُعاد مسبقًا');
            }

            $borrowing->update(['status' => 'returned']);
            $borrowing->book->increment('quantity');

            return redirect()->route('borrowings.index')
                ->with('success', 'تم إرجاع الكتاب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إرجاع الكتاب');
        }
    }
}
