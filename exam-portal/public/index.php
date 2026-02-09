<?php

declare(strict_types=1);

use App\Config\Constants;
use App\Config\RedisSessionHandler;
use App\Controllers\Admin\DashboardController;
use App\Controllers\Admin\ExamController;
use App\Controllers\Auth\LoginController;
use App\Controllers\Student\ExamRunnerController;
use App\Controllers\Student\ResultController;
use App\Controllers\Student\SubmissionController;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

require_once __DIR__ . '/../app/Config/Constants.php';

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $path = __DIR__ . '/../app/' . str_replace('\\', '/', substr($class, strlen($prefix))) . '.php';
    if (is_file($path)) {
        require_once $path;
    }
});

function loadEnv(string $path): void
{
    if (!is_file($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
            continue;
        }

        [$key, $value] = explode('=', $line, 2);
        $_ENV[trim($key)] = trim($value);
    }
}

function view(string $template, array $data = []): void
{
    extract($data, EXTR_SKIP);
    $base = dirname(__DIR__) . '/resources/views/';
    require $base . 'layouts/header.php';
    require $base . $template . '.php';
    require $base . 'layouts/footer.php';
}

loadEnv(dirname(__DIR__) . '/.env');

session_name(Constants::SESSION_NAME);
if (\App\Config\RedisHandler::isAvailable()) {
    $handler = new RedisSessionHandler();
    session_set_save_handler($handler, true);
}
session_start();

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

if ($uri === '/' || $uri === '/login') {
    $controller = new LoginController();
    $method === 'POST' ? $controller->login() : $controller->show();
    exit;
}

if ($uri === '/logout') {
    (new LoginController())->logout();
}

if ($uri === '/admin/dashboard') {
    RoleMiddleware::handle(Constants::ROLE_ADMIN);
    (new DashboardController())->index();
    exit;
}

if ($uri === '/admin/exam/create') {
    RoleMiddleware::handle(Constants::ROLE_ADMIN);
    $controller = new ExamController();
    $method === 'POST' ? $controller->create() : $controller->createForm();
    exit;
}

if ($uri === '/student/dashboard') {
    RoleMiddleware::handle(Constants::ROLE_STUDENT);
    (new ExamRunnerController())->dashboard();
    exit;
}

if (preg_match('#^/student/exam/(\d+)$#', $uri, $matches)) {
    RoleMiddleware::handle(Constants::ROLE_STUDENT);
    (new ExamRunnerController())->startExam((int) $matches[1]);
    exit;
}

if ($uri === '/api/save-progress') {
    AuthMiddleware::handle();
    (new SubmissionController())->saveProgress();
    exit;
}

if ($uri === '/student/exam/submit') {
    RoleMiddleware::handle(Constants::ROLE_STUDENT);
    (new SubmissionController())->submit();
    exit;
}

if ($uri === '/student/report') {
    RoleMiddleware::handle(Constants::ROLE_STUDENT);
    (new ResultController())->report();
    exit;
}

http_response_code(404);
echo '404 Not Found';
