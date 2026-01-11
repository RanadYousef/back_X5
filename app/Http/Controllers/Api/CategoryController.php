<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\Api\SearchCategoryRequest;
use Illuminate\Validation\ValidationException;
use Exception;

class CategoryController extends BaseApiController
{
    /**
     * عرض جميع التصنيفات
     */
    public function index()
    {
        try {
            $categories = Category::select('id', 'name')->get();

            return $this->success($categories, 'Categories retrieved successfully');
        } catch (Exception $e) {
            return $this->error('Failed to load categories');
        }
    }

    /**
     * عرض كتب تصنيف معين
     */
    public function books(Category $category)
    {
        try {
            $category->load('books');

            return $this->success($category, 'Category books retrieved successfully');
        } catch (Exception $e) {
            return $this->error('Failed to load category books');
        }
    }

    /**
     * 🔍 البحث عن تصنيف بالاسم (مرة واحدة فقط)
     */
    public function search(SearchCategoryRequest $request)
    {
        try {
            // ✅ Validation إضافي داخل الكونترولار (حماية إضافية)
            $validated = validator($request->all(), [
                'name' => ['required', 'string'],
            ])->validate();

            $category = Category::where('name', 'LIKE', '%' . $validated['name'] . '%')
                ->withCount('books')
                ->get();

            return $this->success($category, 'Search results retrieved successfully');

        } catch (ValidationException $e) {
            return $this->error(
                'Validation failed',
                422,
                $e->errors()
            );
        } catch (Exception $e) {
            return $this->error('Search failed');
        }
    }
}
