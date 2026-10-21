<?php

namespace App\Http\Requests\UpdateProviderProfileRequest;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProviderProfileRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}

