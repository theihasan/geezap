<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserPreferencesUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'email_frequency' => 'nullable|in:daily,weekly,monthly,never',
            'emails_per_frequency' => 'nullable|integer|min:1|max:20',
            'preferred_job_categories_id' => 'nullable|array',
            'preferred_job_categories_id.*' => 'exists:job_categories,id',
            'preferred_regions_id' => 'nullable|array', 
            'preferred_regions_id.*' => 'exists:countries,id',
            'preferred_job_types' => 'nullable|array',
            'email_notifications_enabled' => 'boolean',
            'show_recommendations' => 'boolean',
            'remote_only' => 'boolean',
            'min_salary' => 'nullable|numeric|min:0',
            'max_salary' => 'nullable|numeric|min:0|gte:min_salary',
        ];
    }

    public function messages(): array
    {
        return [
            'max_salary.gte' => 'Maximum salary must be greater than or equal to minimum salary.',
            'preferred_job_categories_id.*.exists' => 'Selected job category is invalid.',
            'preferred_regions_id.*.exists' => 'Selected region is invalid.',
        ];
    }

    public function getPreferencesData(): array
    {
        return [
            'email_frequency' => $this->input('email_frequency', 'weekly'),
            'emails_per_frequency' => $this->input('emails_per_frequency', 5),
            'preferred_job_categories_id' => $this->input('preferred_job_categories_id', []),
            'preferred_regions_id' => $this->input('preferred_regions_id', []),
            'preferred_job_types' => $this->input('preferred_job_types', []),
            'email_notifications_enabled' => $this->boolean('email_notifications_enabled'),
            'show_recommendations' => $this->boolean('show_recommendations', true),
            'remote_only' => $this->boolean('remote_only'),
            'min_salary' => $this->input('min_salary'),
            'max_salary' => $this->input('max_salary'),
        ];
    }
}