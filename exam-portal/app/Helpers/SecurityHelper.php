<?php

declare(strict_types=1);

namespace App\Helpers;

final class SecurityHelper
{
    public static function csrfToken(): string
    {
        if (empty($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_csrf'];
    }

    public static function validateCsrf(?string $token): bool
    {
        return hash_equals($_SESSION['_csrf'] ?? '', (string) $token);
    }

    public static function e(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}
