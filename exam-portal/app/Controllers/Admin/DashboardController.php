<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Models\Exam;

final class DashboardController
{
    public function index(): void
    {
        $exams = (new Exam())->all();
        view('admin/dashboard', ['title' => 'Admin Dashboard', 'exams' => $exams]);
    }
}
