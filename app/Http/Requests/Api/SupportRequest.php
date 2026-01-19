<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class SupportRequest extends FormRequest
{   /**
     * Summary of authorize
     * @return bool
     */
    public function authorize(): bool
    {
        return true; //support request is open to all users
    }
    /**
     * Summary of rules
     * @return array{email: string[], message: string[], name: string[]}
     */
    public function rules(): array
    {
        return [
            'name'    => ['required', 'string', 'max:100'],
            'email'   => ['required', 'email'],
            'message' => ['required', 'string', 'min:10'],
        ];
    }
}