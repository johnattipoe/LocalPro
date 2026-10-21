<?php

namespace App\Http\Requests\StoreServiceRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() && $this->user()->role === 'customer';
    }

    public function rules(): array
    {
        return [
            'provider_profile_id' => ['required', 'integer', 'exists:provider_profiles,id'],
            'service_category_id' => ['nullable', 'integer', 'exists:service_categories,id'],
            'description' => ['required', 'string', 'min:15', 'max:3000'],
            'city' => ['required', 'in:Ho,Hohoe,Keta,Aflao'],
            'address' => ['nullable', 'string', 'max:255'],
            'preferred_at' => ['nullable', 'date', 'after_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'description.min' => 'Add a little more detail so the professional can understand the job.',
            'preferred_at.after_or_equal' => 'Choose today or a future date.',
        ];
    }
}

