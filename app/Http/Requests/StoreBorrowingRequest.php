<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * فورم ريكوس خاص بعملية استعارة كتاب
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
