<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;

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
    public function index(Request $request)
    {
        $categories = Category::all();

        $query = Book::with('category')
          ->withAvg(['reviews as average_rating' => function ($q) {
             $q->where('status', 'approved');
            }], 'rating')
          ->withCount(['reviews as ratings_count' => function ($q) {
             $q->where('status', 'approved');
            }]);
        
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'LIKE', '%' . $searchTerm . '%')
                    ->orWhere('author', 'LIKE', '%' . $searchTerm . '%');
            });
        }

        $books = $query->latest()->paginate(10)->withQueryString();

        return view('books.index', compact('books', 'categories'));
    }
    /**
     * Show the form for creating a new book.
     */
    public function create()
    {
        $categories = Category::all();

        return view('books.create', compact('categories'));
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
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
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

            $book->delete();

            return redirect()->route('books.index')
                ->with('success', 'Book deleted successfully.');

        } catch (\Exception $e) {
            return back()->with('error', 'Delete failed.');
        }
    }
    /**
     * Display trashed books.
     */
    public function trashed()
    {
        $books = Book::onlyTrashed()->with('category')->paginate(10);
        return view('books.trashed', compact('books'));
    }

    /**
     * Restore a trashed book.
     */
    public function restore($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();

        return redirect()->route('books.trashed')
            ->with('success', 'Book restored successfully.');
    }

    /**
     * Permanently delete a book.
     */
    public function forceDelete($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);

        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->forceDelete();

        return redirect()->route('books.trashed')
            ->with('success', 'Book permanently deleted.');
    }
}