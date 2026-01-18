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
use Throwable;
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
    public function books($id)
    {
        try {
            $category = Category::find($id);

            if (!$category) {
                Log::warning('API: Category not found', ['category_id' => $id]);
                return $this->error('Category not found', 404);
            }

            Log::info('API: Fetching books for category', [
                'category_id' => $category->id,
                'category_name' => $category->name
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

            Log::info('Books retrieved', [
                'category_id' => $category->id,
                'books_count' => $books->count()
            ]);

            return $this->success([
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'books' => $books
            ], 'Category books retrieved successfully');

        } catch (Throwable $e) {
            Log::error('API: Failed to load category books', [
                'category_id' => $id ?? null,
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return $this->error('Failed to load category books', 500);
        }
    }
    /**
     * search categories by name
     */
    public function search(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string', 'min:1', 'max:100'],
            ]);

            Log::info('Category search request', $validated);

            $categories = Category::where('name', 'like', '%' . $validated['name'] . '%')
                ->get();

            if ($categories->isEmpty()) {
                Log::info('No categories found', $validated);
                return $this->success([], 'No categories found', 200);
            }

            Log::info('Categories found', ['count' => $categories->count()]);

            return $this->success($categories, 'Categories found successfully', 200);

        } catch (ValidationException $e) {
            Log::warning('Category search validation failed', ['errors' => $e->errors()]);
            return $this->error('Invalid search parameters', 422);

        } catch (Throwable $e) {
            Log::error('Category search failed', [
                'search_term' => $validated['name'] ?? null,
                'exception' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString()
            ]);

            return $this->error('Failed to search categories', 500);
        }
    }

}