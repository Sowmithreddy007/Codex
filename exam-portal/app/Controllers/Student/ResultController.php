<?php

declare(strict_types=1);

namespace App\Controllers\Student;

use App\Models\Result;

final class ResultController
{
    public function report(): void
    {
        $userId = (int) $_SESSION['user']['id'];
        $examId = (int) ($_GET['exam_id'] ?? 0);
        $report = (new Result())->report($userId, $examId);

        view('student/report', ['title' => 'Report', 'report' => $report, 'examId' => $examId]);
    }
}
