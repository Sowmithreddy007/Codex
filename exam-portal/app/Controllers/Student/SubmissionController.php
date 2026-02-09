<?php

declare(strict_types=1);

namespace App\Controllers\Student;

use App\Config\Constants;
use App\Config\Database;
use App\Config\RedisHandler;
use App\Models\Question;
use App\Models\Result;

final class SubmissionController
{
    public function saveProgress(): void
    {
        header('Content-Type: application/json');

        $userId = (int) $_SESSION['user']['id'];
        $examId = (int) ($_POST['exam_id'] ?? 0);
        $answers = $_POST['answers'] ?? [];

        $key = sprintf(Constants::REDIS_USER_TEMP_ANSWERS_KEY, $userId) . '_exam_' . $examId;
        RedisHandler::instance()->client()->setex($key, Constants::CACHE_TTL_TEMP_ANSWERS, json_encode($answers));

        echo json_encode(['ok' => true]);
    }

    public function submit(): void
    {
        $userId = (int) $_SESSION['user']['id'];
        $examId = (int) ($_POST['exam_id'] ?? 0);
        $tempKey = sprintf(Constants::REDIS_USER_TEMP_ANSWERS_KEY, $userId) . '_exam_' . $examId;

        $redis = RedisHandler::instance()->client();
        $answers = json_decode((string) $redis->get($tempKey), true) ?: [];

        $questions = (new Question())->byExam($examId);
        $map = [];
        foreach ($questions as $question) {
            $map[$question['id']] = $question;
        }

        $pdo = Database::instance()->pdo();
        $pdo->beginTransaction();

        $insert = $pdo->prepare('INSERT INTO user_responses (user_id, exam_id, question_id, selected_option, is_correct) VALUES (?, ?, ?, ?, ?)');

        $correct = 0;
        foreach ($answers as $questionId => $selected) {
            if (!isset($map[$questionId])) {
                continue;
            }

            $isCorrect = strtoupper((string) $selected) === strtoupper((string) $map[$questionId]['correct_option']);
            if ($isCorrect) {
                $correct++;
            }

            $insert->execute([$userId, $examId, (int) $questionId, strtoupper((string) $selected), $isCorrect ? 1 : 0]);
        }

        $total = max(count($questions), 1);
        $score = round(($correct / $total) * 100, 2);
        (new Result())->upsert($userId, $examId, $score);

        $pdo->commit();
        $redis->del($tempKey);

        header('Location: /student/report?exam_id=' . $examId);
        exit;
    }
}
