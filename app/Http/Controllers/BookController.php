<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

/**
 * كنترولر إدارة الكتب
 */
class BookController extends Controller
{
    /**
     * عرض جميع الكتب
     */
    public function index()
    {
        $books = Book::with('category')->get();
        return view('books.index', compact('books'));
    }

    /**
     * إضافة كتاب جديد
     */
    public function store(StoreBookRequest $request)
    {
        try {
            
            Book::create($request->validated());

            return redirect()->route('books.index')
                ->with('success', 'تمت إضافة الكتاب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء إضافة الكتاب');
        }
    }

    /**
     * تعديل كتاب
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            $book->update($request->validated());

            return redirect()->route('books.index')
                ->with('success', 'تم تعديل الكتاب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء تعديل الكتاب');
        }
    }

    /**
     * حذف كتاب
     */
    public function destroy(Book $book)
    {
        try {
            $book->delete();

            return redirect()->route('books.index')
                ->with('success', 'تم حذف الكتاب بنجاح');
        } catch (\Exception $e) {
            return back()->with('error', 'حدث خطأ أثناء حذف الكتاب');
        }
    }
}
