<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    /**
     * Summary of authorize
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    /**
     * Summary of rules
     * @return array{message: string}
     */
    public function rules(): array
    {
        return [
            'message' => 'required|string|min:1|max:1000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     */
    /**
     * Summary of messages
     * @return array{message.max: string, message.min: string, message.required: string, message.string: string}
     */
    public function messages(): array
    {
        return [
            'message.required' => 'الرسالة مطلوبة',
            'message.string' => 'الرسالة يجب أن تكون نص',
            'message.min' => 'الرسالة يجب أن تحتوي على حرف واحد على الأقل',
            'message.max' => 'الرسالة لا يمكن أن تتجاوز 1000 حرف',
        ];
    }
}
