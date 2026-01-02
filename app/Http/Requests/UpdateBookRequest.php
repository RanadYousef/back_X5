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
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'copies_number'    => 'required|integer|min:0',
        ];
    }
}
