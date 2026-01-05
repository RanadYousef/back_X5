<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * فورم ريكوست خاص بتعديل بيانات كتاب
 */
class UpdateBookRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
