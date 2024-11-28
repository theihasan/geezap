<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateExperienceRequest extends FormRequest
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
            'position' => ['required', 'array'],
            'position.*' => ['required', 'string', 'max:100'],
            'company_name' => ['required', 'array'],
            'currently_working' => [],
            'start_date' => ['required', 'array'],
            'end_date' => ['sometimes', 'array'],
            'description' => ['sometimes', 'array'],
        ];
    }
}
