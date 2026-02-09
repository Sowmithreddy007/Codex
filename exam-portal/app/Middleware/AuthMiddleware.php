<?php

declare(strict_types=1);

namespace App\Middleware;

final class AuthMiddleware
{
    public static function handle(): void
    {
        if (empty($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }
}
