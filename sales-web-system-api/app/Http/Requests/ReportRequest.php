<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date']
        ];
    }

    public function messages(): array
    {
        return [
            'date.required' => 'A data é obrigatória',
            'date.date' => 'A data deve ser uma data válida'
        ];
    }
}
