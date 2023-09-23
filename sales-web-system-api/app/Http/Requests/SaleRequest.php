<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SaleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'value' => ['required', 'numeric'],
            'date' => ['required', 'dateformat:Y-m-d'],
            'seller_id' => ['required', 'integer', 'exists:sellers,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'value.required' => 'O campo valor é obrigatário.',
            'value.decimal' => 'O campo valor deve ser numérico.',
            'date.required' => 'O campo data é obrigatário.',
            'date.dateformat' => 'O campo data deve ter o formato YYYY-MM-DD.',
            'seller_id.required' => 'O campo vendedor é obrigatório.',
            'seller_id.integer' => 'O campo vendedor deve ser numérico.',
            'seller_id.exists' => 'O vendedor informado não existe.',
        ];
    }
}
