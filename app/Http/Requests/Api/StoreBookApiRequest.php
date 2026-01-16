<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * API Request for storing a new book
 */
class StoreBookApiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // You may add authorization logic here, e.g., check if user is admin
    }

    /**
     * Get the validation rules that apply to the request.
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
