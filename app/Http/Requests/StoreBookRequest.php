<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreBookRequest
 *
 * Handles validation and authorization logic
 * for storing a newly created book.
 */
class StoreBookRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized
     * to perform this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {

        return true;
    }

    /**
     * Get the validation rules that apply
     * to the request data.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'category_id'   => 'required|exists:categories,id',
            'title'         => 'required|string|max:255',
            'author'        => 'required|string|max:255',
            'description'   => 'required|string',
            'publish_year'  => 'required|integer|min:1000|max:' . date('Y'),
            'language'      => 'required|string|max:50',
            'copies_number' => 'required|integer|min:0',
            'cover_image'   => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }
}
