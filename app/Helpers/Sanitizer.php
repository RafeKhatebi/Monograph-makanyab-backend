<?php

namespace App\Helpers;

class Sanitizer
{
    /**
     * Sanitize a string input by removing unwanted characters and encoding HTML entities.
     */
    public static function sanitize(string $input): string
    {
        // Remove null bytes
        $input = str_replace(chr(0), '', $input);

        // Strip HTML tags (keep text only)
        $input = strip_tags($input);

        // Convert special characters to HTML entities
        $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

        // Trim whitespace
        $input = trim($input);

        return $input;
    }

    /**
     * Sanitize an array of inputs recursively.
     */
    public static function sanitizeArray(array $inputs): array
    {
        $sanitized = [];
        foreach ($inputs as $key => $value) {
            if (is_array($value)) {
                $sanitized[self::sanitize($key)] = self::sanitizeArray($value);
            } else {
                $sanitized[self::sanitize($key)] = self::sanitize((string) $value);
            }
        }

        return $sanitized;
    }

    /**
     * Sanitize rich text content (allows safe HTML tags).
     */
    public static function sanitizeRichText(string $input): string
    {
        // Allow basic formatting tags
        $allowedTags = '<p><br><strong><b><em><i><u><h1><h2><h3><h4><h5><h6><ul><ol><li><a><img><blockquote><pre><code>';

        $input = strip_tags($input, $allowedTags);
        $input = trim($input);

        return $input;
    }

    /**
     * Validate and sanitize a URL.
     */
    public static function sanitizeUrl(string $url): string
    {
        $url = trim($url);
        $url = filter_var($url, FILTER_SANITIZE_URL);

        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return '';
        }

        // Only allow http and https protocols
        $scheme = parse_url($url, PHP_URL_SCHEME);
        if (! in_array($scheme, ['http', 'https'])) {
            return '';
        }

        return $url;
    }

    /**
     * Sanitize an email address.
     */
    public static function sanitizeEmail(string $email): string
    {
        $email = trim($email);
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }
}
