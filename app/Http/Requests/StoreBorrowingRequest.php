<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * FormRequest is a special tool for the book borrowing process.
 */
class StoreBorrowingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
