<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'image' => [$this->isMethod('POST') ? 'nullable' : 'nullable', 'image', 'max:4096'],
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'brand.required' => 'La marca es obligatoria.',
            'description.required' => 'La descripción es obligatoria.',
            'price.required' => 'El precio es obligatorio.',
            'price.numeric' => 'El precio debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',
            'categories.required' => 'Debés seleccionar al menos una categoría.',
            'categories.min' => 'Debés seleccionar al menos una categoría.',
            'categories.*.exists' => 'Una de las categorías seleccionadas no es válida.',
        ];
    }
}
