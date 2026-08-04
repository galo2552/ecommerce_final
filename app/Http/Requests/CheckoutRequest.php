<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        return [
            'address_id' => ['nullable', 'exists:addresses,id'],
            
            'street' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'city' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'province' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'zip_code' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
        ];
    }
}
