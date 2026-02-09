<?php

declare(strict_types=1);

namespace App\Middleware;

final class RoleMiddleware
{
    public static function handle(string $role): void
    {
        AuthMiddleware::handle();

        if (($_SESSION['user']['role'] ?? null) !== $role) {
            http_response_code(403);
            echo 'Forbidden';
            exit;
        }
    }
}
