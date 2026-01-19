<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class ReturnBookRequest
 *
 * Handles validation for returning a borrowed book
 * via the API endpoints.
 */
class ReturnBookRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized
     * to perform this request.
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
     * Get the validation rules for returning a book.
     *
     * Ensures that the provided borrowing ID exists.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'borrowing_id' => 'required|exists:borrowings,id',
        ];
    }
}
