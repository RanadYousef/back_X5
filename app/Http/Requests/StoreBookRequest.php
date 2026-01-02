<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * فورم ريكوست خاص بعملية إضافة كتاب جديد
 */
class StoreBookRequest extends FormRequest
{
    /**
     * تحديد من يحق له تنفيذ الطلب
     */
    public function authorize(): bool
    {
        
        return true;
    }

    /**
     * قواعد التحقق من صحة البيانات
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'quantity'    => 'required|integer|min:1',
        ];
    }
}
