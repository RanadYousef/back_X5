<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request for the process of adding a new book
 */
class StoreBookRequest extends FormRequest
{
    /**
     * Determining who is entitled to execute the request
     */
    public function authorize(): bool
    {
        
        return true;
    }

    /**
     * Data validation rules
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
