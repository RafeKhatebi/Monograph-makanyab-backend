<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StrongPassword implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $password = (string) $value;

        if (strlen($password) < 8) {
            $fail('The password must be at least 8 characters long.');

            return;
        }

        if (! preg_match('/[A-Z]/', $password)) {
            $fail('The password must contain at least one uppercase letter.');

            return;
        }

        if (! preg_match('/[a-z]/', $password)) {
            $fail('The password must contain at least one lowercase letter.');

            return;
        }

        if (! preg_match('/[0-9]/', $password)) {
            $fail('The password must contain at least one number.');

            return;
        }

        if (! preg_match('/[^A-Za-z0-9]/', $password)) {
            $fail('The password must contain at least one special character.');

            return;
        }

        // Check for common passwords
        $commonPasswords = [
            'password', '12345678', 'qwerty123', 'admin123', 'letmein123',
            'welcome123', 'monkey123', 'dragon123', 'master123', 'abc12345',
        ];

        if (in_array(strtolower($password), $commonPasswords)) {
            $fail('The password is too common. Please choose a stronger password.');

            return;
        }
    }
}
