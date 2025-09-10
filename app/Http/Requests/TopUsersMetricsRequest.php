<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TopUsersMetricsRequest extends FormRequest
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
            'limit' => 'nullable|integer|min:1|max:100'
        ];
    }

    public function messages(): array
    {
        return [
            'limit.max' => 'No se pueden solicitar más de 100 usuarios top',
            'limit.min' => 'El límite debe ser al menos 1'
        ];
    }
}
