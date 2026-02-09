<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class User
{
    public function findByEmail(string $email): ?array
    {
        $stmt = Database::instance()->pdo()->prepare('SELECT * FROM users WHERE email = ? LIMIT 1');
        $stmt->execute([$email]);

        return $stmt->fetch() ?: null;
    }

    public function studentsByBatch(string $batchId): array
    {
        $stmt = Database::instance()->pdo()->prepare("SELECT * FROM users WHERE role='student' AND batch_id=?");
        $stmt->execute([$batchId]);

        return $stmt->fetchAll();
    }
}
