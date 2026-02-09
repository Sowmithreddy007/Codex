<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class Exam
{
    public function create(array $data): int
    {
        $stmt = Database::instance()->pdo()->prepare(
            'INSERT INTO exams (title, duration, start_time, end_time, created_by) VALUES (?, ?, ?, ?, ?)'
        );
        $stmt->execute([$data['title'], $data['duration'], $data['start_time'], $data['end_time'], $data['created_by']]);

        return (int) Database::instance()->pdo()->lastInsertId();
    }

    public function assignToBatch(int $examId, string $batchId): void
    {
        $stmt = Database::instance()->pdo()->prepare('INSERT INTO exam_assignments (exam_id, target_batch_id) VALUES (?, ?)');
        $stmt->execute([$examId, $batchId]);
    }

    public function forBatch(string $batchId): array
    {
        $stmt = Database::instance()->pdo()->prepare(
            'SELECT e.* FROM exams e JOIN exam_assignments ea ON ea.exam_id=e.id WHERE ea.target_batch_id=? ORDER BY e.start_time DESC'
        );
        $stmt->execute([$batchId]);

        return $stmt->fetchAll();
    }

    public function find(int $id): ?array
    {
        $stmt = Database::instance()->pdo()->prepare('SELECT * FROM exams WHERE id=?');
        $stmt->execute([$id]);

        return $stmt->fetch() ?: null;
    }

    public function all(): array
    {
        return Database::instance()->pdo()->query('SELECT * FROM exams ORDER BY id DESC')->fetchAll();
    }
}
