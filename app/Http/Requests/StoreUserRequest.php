<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
/**
 * Summary of StoreUserRequest
 */
class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    // validation rules for creating user
    /**
     * Summary of rules
     * @return array{email: string, name: string, password: string, role: string}
     */
    public function rules(): array
    {
        return [
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|exists:roles,name',
        ];
    }
}
