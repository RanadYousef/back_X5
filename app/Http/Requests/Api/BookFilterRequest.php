<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class BookFilterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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