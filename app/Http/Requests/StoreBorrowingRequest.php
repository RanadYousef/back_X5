<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class StoreBorrowingRequest
 *
 * Handles validation for creating a new borrowing record.
 *
 * This request is used to ensure that:
 * - The user exists
 * - The book exists
 * - The borrowing date is valid
 * - The borrowing status is allowed
 *
 * Used during the book borrowing process.
 */
class StoreBorrowingRequest extends FormRequest
{
    /**
     * Determine whether the user is authorized to make this request.
     *
     * @return bool
     */    
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules for the borrowing request.
     *
     * Validation rules ensure:
     * - The user exists in the system
     * - The book exists in the system
     * - The borrowing date is not in the future
     * - The borrowing status is valid
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date|before_or_equal:today',
            'status' => 'required|in:borrowed,returned,overdue',
        ];
    }
}
