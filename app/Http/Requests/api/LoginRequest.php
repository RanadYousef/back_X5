<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Summary of LoginRequest
 */
class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;// true to allow all users to make this request
    }

    public function rules(): array
    {
        return [
            'email'    => 'required|email',
            'password' => 'required|string',
        ];
    }
}
