<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRoleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|unique:roles,name|max:255',
            'permissions' => 'required|array|min:1',
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'يجب إدخال اسم الدور.',
            'name.unique' => 'هذا الدور موجود مسبقاً.',
            'permissions.required' => 'يجب اختيار صلاحية واحدة على الأقل لهذا الدور.',
        ];
    }
}
