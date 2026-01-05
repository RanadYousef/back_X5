<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Support\Facades\Storage;

/**
 * Class BookController
 *
 * Handles CRUD operations for books.
 */
class BookController extends Controller
{
    /**
     * Display a listing of books.
     */
    public function index()
    {
        $books = Book::with('category')->latest()->paginate(10);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        return view('books.create');
    }

    /**
     * Store a newly created book.
     */
    public function store(StoreBookRequest $request)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('cover_image')) {
                $data['cover_image'] = $request->file('cover_image')
                    ->store('books/covers', 'public');
            }

            Book::create($data);

            return redirect()->route('books.index')
                ->with('success', 'Book created successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Failed to create book.');
        }
    }

    /**
     * Show the form for editing the specified book.
     */
    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified book.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $data = $request->validated();

        try {
            if ($request->hasFile('cover_image')) {


                if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                    Storage::disk('public')->delete($book->cover_image);
                }


                $data['cover_image'] = $request->file('cover_image')
                    ->store('books/covers', 'public');
            }

            $book->update($data);

            return redirect()->route('books.index')
                ->with('success', 'Book updated successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Update failed.');
        }
    }

    /**
     * Remove the specified book.
     */
    public function destroy(Book $book)
    {
        try {

            if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
                Storage::disk('public')->delete($book->cover_image);
            }

            $book->delete();

            return redirect()->route('books.index')
                ->with('success', 'Book deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed.');
        }
    }
}