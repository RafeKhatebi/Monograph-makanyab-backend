<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateProfileRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:users,username,'.$this->user()->id,
            'lastname' => 'nullable|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->user()->id,
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|file|image|mimetypes:image/jpeg,image/png,image/webp|mimes:jpg,jpeg,png,webp|max:2048',
            'current_password' => 'nullable|required_with:password|current_password',
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ];
    }
}
