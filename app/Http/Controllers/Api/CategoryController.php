<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SearchCategoryRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Exception;

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

            $category->load('books');

            return $this->success($category, 'Category books retrieved successfully');

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
    public function search(SearchCategoryRequest $request)
    {
        try {
            Log::info('API: Searching category', [
                'search_term' => $request->name
            ]);

            // Validation inside controller (extra safety)
            $validated = validator($request->all(), [
                'name' => ['required', 'string', 'min:2'],
            ])->validate();

            $categories = Category::where('name', 'LIKE', '%' . $validated['name'] . '%')
                ->withCount('books')
                ->get();

            return $this->success($categories, 'Search results retrieved successfully');

        } catch (ValidationException $e) {

            Log::warning('API: Category search validation failed', [
                'errors' => $e->errors()
            ]);

            return $this->error(
                'Validation failed',
                422,
                $e->errors()
            );

        } catch (Exception $e) {

            Log::error('API: Category search failed', [
                'exception' => $e->getMessage()
            ]);

            return $this->error('Search failed');
        }
    }
}
