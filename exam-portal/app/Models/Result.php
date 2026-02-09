<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class Result
{
    public function upsert(int $userId, int $examId, float $score): void
    {
        $stmt = Database::instance()->pdo()->prepare(
            'INSERT INTO exam_results (user_id, exam_id, score, submitted_at) VALUES (?, ?, ?, NOW()) ON DUPLICATE KEY UPDATE score=VALUES(score), submitted_at=NOW()'
        );
        $stmt->execute([$userId, $examId, $score]);
    }

    public function report(int $userId, int $examId): ?array
    {
        $pdo = Database::instance()->pdo();

        $avgStmt = $pdo->prepare('SELECT AVG(score) AS batch_avg FROM exam_results WHERE exam_id = ?');
        $avgStmt->execute([$examId]);
        $avg = (float) ($avgStmt->fetch()['batch_avg'] ?? 0);

        $rankStmt = $pdo->prepare(
            'SELECT rank_pos, score FROM (SELECT user_id, score, RANK() OVER (ORDER BY score DESC, submitted_at ASC) AS rank_pos FROM exam_results WHERE exam_id=?) ranked WHERE user_id=?'
        );
        $rankStmt->execute([$examId, $userId]);
        $row = $rankStmt->fetch();

        if (!$row) {
            return null;
        }

        return [
            'score' => (float) $row['score'],
            'rank' => (int) $row['rank_pos'],
            'batch_avg' => $avg,
        ];
    }
}
