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
 * Handles CRUD operations for books in the admin panel,
 * including listing, filtering, creating, updating,
 * soft deleting, restoring, and permanently deleting books.
 */
class BookController extends Controller
{
    /**
     * Display a paginated list of books with filters.
     *
     * Supports filtering by category and searching by
     * title or author. Also loads average rating and
     * ratings count for approved reviews.
     *
     * @param Request $request
     * @return \Illuminate\View\View
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
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created book in storage.
     *
     * Handles validation, image upload, and
     * saves the book record to the database.
     *
     * @param StoreBookRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
     *
     * @param Book $book
     * @return \Illuminate\View\View
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified book in storage.
     *
     * Handles updating book data and replacing
     * the cover image if a new one is uploaded.
     *
     * @param UpdateBookRequest $request
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse
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
     * Soft delete the specified book.
     *
     * Marks the book as deleted without
     * removing it permanently from the database.
     *
     * @param Book $book
     * @return \Illuminate\Http\RedirectResponse
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
     * Display a list of soft deleted books.
     *
     * @return \Illuminate\View\View
     */
    public function trashed()
    {
        $books = Book::onlyTrashed()->with('category')->paginate(10);
        return view('books.trashed', compact('books'));
    }

    /**
     * Restore a soft deleted book.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function restore($id)
    {
        $book = Book::onlyTrashed()->findOrFail($id);
        $book->restore();

        return redirect()->route('books.trashed')
            ->with('success', 'Book restored successfully.');
    }

    /**
     * Permanently delete a book from storage.
     *
     * Also deletes the associated cover image
     * from disk if it exists.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
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