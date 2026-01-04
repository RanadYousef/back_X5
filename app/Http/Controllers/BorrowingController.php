<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Book;
use App\Http\Requests\StoreBorrowingRequest;
use Illuminate\Support\Facades\DB;

/**
 * Class BorrowingController
 *
 * Handles borrowing operations.
 */
class BorrowingController extends Controller
{
    /**
     * Display a listing of borrowings.
     */
    public function index()
    {
        $borrowings = Borrowing::with(['book', 'user'])->get();
        return view('borrowings.index', compact('borrowings'));
    }

    /**
     * Show the form for creating a new borrowing.
     */
    public function create()
    {
        return view('borrowings.create');
    }

    /**
     * Store a newly created borrowing.
     */
    public function store(StoreBorrowingRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {

                $data = $request->validated();

                $book = Book::lockForUpdate()->findOrFail($data['book_id']);

                if ($book->copies_number < 1) {
                    throw new \Exception('Book not available');
                }

                Borrowing::create([
                    'book_id' => $book->id,
                    'user_id' => auth()->id(),
                    'status'  => 'borrowed',
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
     */
    public function returnBook(Borrowing $borrowing)
    {
        try {
            DB::transaction(function () use ($borrowing) {

                if ($borrowing->status === 'returned') {
                    throw new \Exception('Already returned');
                }

                $borrowing->update(['status' => 'returned']);

                $borrowing->book->increment('copies_number');
            });

            return redirect()->route('borrowings.index')
                ->with('success', 'Book returned successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Return operation failed.');
        }
    }
}