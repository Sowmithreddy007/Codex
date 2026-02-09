<?php

declare(strict_types=1);

namespace App\Controllers\Student;

use App\Config\Constants;
use App\Config\RedisHandler;
use App\Models\Exam;
use App\Models\Question;

final class ExamRunnerController
{
    public function dashboard(): void
    {
        $batchId = (string) ($_SESSION['user']['batch_id'] ?? '');
        $exams = (new Exam())->forBatch($batchId);

        view('student/dashboard', ['title' => 'Student Dashboard', 'exams' => $exams]);
    }

    public function startExam(int $id): void
    {
        $redis = RedisHandler::instance()->client();
        $key = sprintf(Constants::REDIS_EXAM_QUESTIONS_KEY, $id);

        $payload = $redis->get($key);
        if (!$payload) {
            $questions = (new Question())->byExam($id);
            $payload = json_encode($questions, JSON_THROW_ON_ERROR);
            $redis->setex($key, Constants::CACHE_TTL_EXAM_QUESTIONS, $payload);
        }

        $questions = json_decode((string) $payload, true, flags: JSON_THROW_ON_ERROR);
        $exam = (new Exam())->find($id);

        view('student/take_exam', ['title' => 'Take Exam', 'exam' => $exam, 'questions' => $questions]);
    }
}
