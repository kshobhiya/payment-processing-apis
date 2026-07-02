<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessPaymentRequest extends FormRequest
{
    /**
     * Authorize Request
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation Rules
     */
    public function rules(): array
    {
        return [
            'payment_method' => 'required|string|max:100',
        ];
    }

    /**
     * Custom Messages
     */
    public function messages(): array
    {
        return [
            'payment_method.required' => 'Payment Method is required.'
        ];
    }
}
