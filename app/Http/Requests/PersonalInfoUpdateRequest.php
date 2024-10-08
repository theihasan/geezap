<?php

namespace App\Http\Requests;

use App\Enums\Timezone;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PersonalInfoUpdateRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'dob' => ['nullable', 'date'],
            'state' => ['nullable', 'string'],
            'country' => ['nullable', 'string'],
            'occupation' => ['nullable', 'string'],
            'timezone'  => ['nullable', 'string', Rule::in(Timezone::toValues())],
            'postcode' => ['nullable', 'string'],
            'phone' => ['nullable', 'string'],
            'bio' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'address.string' => 'Address must be a string',
            'dob.date' => 'Date of birth must be a valid date',
            'state.string' => 'State must be a string',
            'country.string' => 'Country must be a string',
            'occupation.string' => 'Occupation must be a string',
            'timezone.string' => 'Timezone must be a string',
            'postcode.string' => 'Postcode must be a string',
            'phone.string' => 'Phone must be a string',
            'bio.string' => 'Bio must be a string',
        ];
    }
}
