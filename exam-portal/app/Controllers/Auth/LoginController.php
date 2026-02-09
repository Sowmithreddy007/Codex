<?php

declare(strict_types=1);

namespace App\Controllers\Auth;

use App\Config\Constants;
use App\Models\User;

final class LoginController
{
    public function show(): void
    {
        view('auth/login', ['title' => 'Login']);
    }

    public function login(): void
    {
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $user = (new User())->findByEmail($email);

        if (!$user || !password_verify($password, $user['password_hash'])) {
            view('auth/login', ['title' => 'Login', 'error' => 'Invalid credentials.']);
            return;
        }

        $_SESSION['user'] = [
            'id' => (int) $user['id'],
            'name' => $user['name'],
            'email' => $user['email'],
            'role' => $user['role'],
            'batch_id' => $user['batch_id'],
        ];

        if ($user['role'] === Constants::ROLE_ADMIN) {
            header('Location: /admin/dashboard');
            exit;
        }

        header('Location: /student/dashboard');
        exit;
    }

    public function logout(): void
    {
        $_SESSION = [];
        session_destroy();

        header('Location: /login');
        exit;
    }
}
