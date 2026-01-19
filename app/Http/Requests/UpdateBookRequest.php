<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class UpdateBookRequest
 *
 * Handles validation and authorization logic
 * for updating an existing book.
 */
class UpdateBookRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized
     * to perform this update request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply
     * to the update request.
     *
     * Uses "sometimes|required" to allow
     * partial updates (PATCH-like behavior).
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id'   => 'sometimes|required|exists:categories,id',
            'title'         => 'sometimes|required|string|max:255',
            'author'        => 'sometimes|required|string|max:255',
            'description'   => 'sometimes|required|string',
            'publish_year'  => 'sometimes|required|integer|min:1000|max:' . date('Y'),
            'language'      => 'sometimes|required|string|max:50',
            'copies_number' => 'sometimes|required|integer|min:0',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
