<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePaymentOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'customer.first_name' => 'required|string|max:100',
            'customer.last_name' => 'required|string|max:100',
            'customer.email' => 'required|email',
            'customer.phone' => 'required|string|max:20',
            'customer.address' => 'required|string',
            'customer.city' => 'required|string|max:100',
            'customer.country' => 'required|string|max:100',
            'amount' => 'required|numeric|min:1',
            'currency' => 'required|string|size:3',
            'description' => 'required|string',
        ];
    }
}
