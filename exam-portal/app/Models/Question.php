<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class Question
{
    public function bulkInsert(int $examId, array $rows): void
    {
        $pdo = Database::instance()->pdo();
        $stmt = $pdo->prepare(
            'INSERT INTO questions (exam_id, text, option_a, option_b, option_c, option_d, correct_option, topic_tag) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );

        foreach ($rows as $row) {
            $stmt->execute([
                $examId,
                $row['text'],
                $row['option_a'],
                $row['option_b'],
                $row['option_c'],
                $row['option_d'],
                $row['correct_option'],
                $row['topic_tag'],
            ]);
        }
    }

    public function byExam(int $examId): array
    {
        $stmt = Database::instance()->pdo()->prepare('SELECT * FROM questions WHERE exam_id=? ORDER BY id');
        $stmt->execute([$examId]);

        return $stmt->fetchAll();
    }
}
