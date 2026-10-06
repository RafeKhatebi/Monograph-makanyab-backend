<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class ProcessSuggestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'admin_note' => [$this->routeIs('admin.*.request-changes') ? 'required' : 'nullable', 'string', 'max:2000'],
        ];
    }
}
