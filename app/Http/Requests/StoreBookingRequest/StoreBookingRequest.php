<?php

namespace App\Http\Requests\StoreBookingRequest;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    public function authorize(): bool { return true; }
    public function rules(): array { return []; }
}

