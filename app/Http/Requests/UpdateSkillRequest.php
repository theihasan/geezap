<?php

namespace App\Http\Requests;

use App\Enums\SkillProficiency;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSkillRequest extends FormRequest
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
            'skill' => ['required', 'array'],
            'skill_level' => ['required', 'array', Rule::in(SkillProficiency::toValues())],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'skills.required' => 'The skills field is required',
            'skills.array' => 'The skills field must be an array',
            'skill_level.required' => 'The skill level field is required',
            'skill_level.string' => 'The skill level field must be a string',
            'skill_level.enum' => 'The skill level field must be a valid skill level',
        ];
    }
}
