<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\DB;

/**
 * Handles CRUD operations for books.
 */
class BookController extends Controller
{
    /**
     * Display all books.
     */
    public function index()
    {
        $books = Book::with('category')->get();
        return view('books.index', compact('books'));
    }

    /**
     * Store a new book.
     */
    public function store(StoreBookRequest $request)
    {
        try {
            DB::transaction(function () use ($request) {
                Book::create($request->validated());
            });

            return redirect()->route('books.index')
                ->with('success', 'Book created successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create book.');
        }
    }

    /**
     * Update a book.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        try {
            DB::transaction(function () use ($request, $book) {
                $book->update($request->validated());
            });

            return redirect()->route('books.index')
                ->with('success', 'Book updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update book.');
        }
    }

    /**
     * Delete a book.
     */
    public function destroy(Book $book)
    {
        try {
            DB::transaction(function () use ($book) {
                $book->delete();
            });

            return redirect()->route('books.index')
                ->with('success', 'Book deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete book.');
        }
    }
}