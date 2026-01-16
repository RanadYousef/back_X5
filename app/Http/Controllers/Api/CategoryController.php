<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SearchCategoryRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\BaseApiController;
class CategoryController extends BaseApiController
{
    /**
     * index categories
     */
    public function index()
    {
        try {
            Log::info('API: Fetching categories list');

            $categories = Category::select('id', 'name')->get();

            return $this->success($categories, 'Categories retrieved successfully');

        } catch (Exception $e) {

            Log::error('API: Failed to load categories', [
                'exception' => $e->getMessage()
            ]);

            return $this->error('Failed to load categories');
        }
    }

    /**
     * index books by category
     */
public function books(Category $category)
{
    try {
        Log::info('API: Fetching books for category', [
            'category_id' => $category->id
        ]);

        $books = $category->books()
            ->select(
                'id',
                'category_id',
                'title',
                'author',
                'description',
                'publish_year',
                'cover_image',
                'language',
                'copies_number'
            )
            ->get();

        return $this->success([
            'category' => [
                'id'   => $category->id,
                'name' => $category->name,
            ],
            'books' => $books
        ], 'Category books retrieved successfully');

    } catch (Exception $e) {

        Log::error('API: Failed to load category books', [
            'category_id' => $category->id,
            'exception'   => $e->getMessage()
        ]);

        return $this->error('Failed to load category books');
    }
}
    /**
     * search categories by name
     */
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:2', 'max:100'],
            ]);

            Log::info('Category search request', $validated);

            $category = Category::where('name', 'like', '%' . $validated['name'] . '%')
                ->firstOrFail();

            Log::info('Category found', ['category_id' => $category->id]);

            return $this->success($category, 'search successful');

        } catch (ValidationException $e) {
            Log::warning('Category search validation failed', ['errors' => $e->errors()]);
            return $this->error('Invalid search parameters', 422);
        } catch (ModelNotFoundException $e) {
            Log::warning('Category not found', $validated);
            return $this->error('search failed', 404);
        }
    }
}