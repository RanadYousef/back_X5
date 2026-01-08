<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Http\Requests\StoreBorrowingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

/**
 * Class BorrowingController
 *
 * Handles book borrowing and returning operations.
 */
class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $borrowings = Borrowing::with(['book', 'user'])->latest()->get();
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $books = Book::where('copies_number', '>', 0)->get();
        return view('borrowings.create', compact('books'));
    }

    /**
     * Store a newly created borrowing in storage.
     *
     * @param StoreBorrowingRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBorrowingRequest $request)
    {
        $data = $request->validated();

        try {
            DB::transaction(function () use ($data) {

                $book = Book::lockForUpdate()->findOrFail($data['book_id']);

                if ($book->copies_number < 1) {
                    throw new \Exception('Book not available.');
                }

                Borrowing::create([
                    'book_id' => $book->id,
                    'user_id' => Auth::id(),
                    'borrowed_at' => now(),
                    'status' => 'borrowed',
                ]);

                $book->decrement('copies_number');
            });

            return redirect()->route('borrowings.index')
                ->with('success', 'Book borrowed successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Borrowing operation failed.');
        }
    }

    /**
     * Return a borrowed book.
     *
     * @param Borrowing $borrowing
     * @return \Illuminate\Http\RedirectResponse
     */
    public function returnBook(Borrowing $borrowing)
    {
        try {
            if ($borrowing->status === 'returned') {
                return back()->with('error', 'This book has already been returned.');
            }

            DB::transaction(function () use ($borrowing) {

                $borrowing->update([
                    'status' => 'returned',
                    'returned_at' => now(),
                ]);
                $book = $borrowing->book()->lockForUpdate()->first();
                $book->increment('copies_number');
            });

            return redirect()->route('borrowings.index')
                ->with('success', 'Book returned successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Return operation failed.');
        }
    }
}
