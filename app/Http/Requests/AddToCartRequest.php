<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'size' => ['required', 'string', 'max:10'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
