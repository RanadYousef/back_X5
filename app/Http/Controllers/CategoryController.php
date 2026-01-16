<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Support\Facades\Log;
use Exception;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories with book counts.
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = Category::select('id', 'name')
            ->withCount('books');

        // Search by category name
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category in storage.
     * @param StoreCategoryRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Book category added successfully');

        } catch (Exception $e) {
            Log::error("Category Store Error: " . $e->getMessage());
            //Redirect the user back to the previous page
            return redirect()->back()
                ->withInput()// Flash the input data back to the previous page to avoid re-typing
                ->with('error', 'an error occurred while saving. Please try again later.');
        }
    }

    /**
     * Show the form for editing the specified category.
     * @param Category $category
     * @return \Illuminate\View\View
     */
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     * @param UpdateCategoryRequest $request
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());

            return redirect()->route('categories.index')
                ->with('success', 'Category name updated successfully');

        } catch (Exception $e) {
            Log::error("Category Update Error: " . $e->getMessage());

            return redirect()->back()
                ->withInput()
                ->with('error', 'Update failed. This name might already exist');
        }
    }

    /**
     * Display the specified category and its paginated books.
     * @param Category $category
     * @return \Illuminate\View\View
     */
    public function show(Category $category)
    {
        $books = $category->books()->paginate(10);

        return view('admin.categories.show', compact('category', 'books'));
    }

    /**
     * Remove the specified category from storage.
     * @param Category $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Category $category)
    {
        try {
            $category->delete();

            return redirect()->route('categories.index')
                ->with('success', 'Category deleted successfully');

        } catch (Exception $e) {
            Log::error("Category Delete Error: " . $e->getMessage());

            return redirect()->back()
                ->with('error', ' this category cannot be deleted');
        }
    }
    public function trash()
    {
        $deletedCategories = Category::onlyTrashed()->get();
        return view('admin.categories.trash', compact('deletedCategories'));
    }

    public function restore($id)
    {
        Category::withTrashed()->find($id)->restore();
        return redirect()->route('categories.trash')->with('success', 'Category restored successfully!');
    }

    public function forceDelete($id)
    {
        Category::withTrashed()->find($id)->forceDelete();
        return redirect()->route('categories.trash')->with('success', 'Category deleted permanently!');
    }

}