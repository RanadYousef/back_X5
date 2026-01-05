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
    public function index()
{
    // Fetch only the ID and Name
    $categories = Category::select('id', 'name')
                          ->withCount('books') // Retrieve the total count of books only    
                          ->latest()
                          ->get();
                          
    return view('admin.categories.index', compact('categories'));
}

    public function create()
    {   
        return view('admin.categories.create');
    }

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

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

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
    public function show(Category $category)
{
    $books = $category->books()->paginate(10); 

    return view('admin.categories.show', compact('category', 'books'));
}

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
   
}
