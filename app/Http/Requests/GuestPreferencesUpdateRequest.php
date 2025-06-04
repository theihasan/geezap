<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GuestPreferencesUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'preferred_job_categories_id' => 'nullable|array',
            'preferred_job_categories_id.*' => 'exists:job_categories,id',
            'preferred_regions_id' => 'nullable|array',
            'preferred_regions_id.*' => 'exists:countries,id',
            'preferred_job_types' => 'nullable|array',
            'email' => 'nullable|email|max:255',
            'remote_only' => 'boolean',
            'email_alerts_enabled' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'email.email' => 'Please enter a valid email address.',
            'preferred_job_categories_id.*.exists' => 'Selected job category is invalid.',
            'preferred_regions_id.*.exists' => 'Selected region is invalid.',
        ];
    }

    public function getPreferencesData(): array
    {
        return [
            'email' => $this->input('email'),
            'preferred_job_categories_id' => $this->input('preferred_job_categories_id', []),
            'preferred_regions_id' => $this->input('preferred_regions_id', []),
            'preferred_job_types' => $this->input('preferred_job_types', []),
            'remote_only' => $this->boolean('remote_only'),
            'email_alerts_enabled' => $this->boolean('email_alerts_enabled'),
        ];
    }
}