<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subject' => ['required', 'string'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string'],
            'email' => ['required', 'email'],
        ];
    }
}
