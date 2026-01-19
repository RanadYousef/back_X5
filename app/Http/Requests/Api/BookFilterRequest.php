<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class BookFilterRequest
 *
 * Handles validation and authorization
 * for filtering and sorting books
 * in the Books API endpoints.
 */
class BookFilterRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized
     * to perform this filter request.
     *
     * Authorization is handled via middleware.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules for
     * filtering, sorting, and paginating books.
     *
     * All fields are optional and used
     * only if provided in the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'search'      => 'nullable|string|max:255',
            'category_id' => 'nullable|integer|min:1',
            'language' => 'nullable|string|max:50',
            'publish_year' => 'nullable|integer|min:1000|max:' . date('Y'),
            'per_page' => 'nullable|integer|min:1|max:100',
            'sort' => 'nullable|in:rating,year,title',
        ];
    }
}
