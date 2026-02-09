<?php

declare(strict_types=1);

namespace App\Models;

use App\Config\Database;

final class Batch
{
    public function all(): array
    {
        return Database::instance()->pdo()->query('SELECT * FROM batches ORDER BY id')->fetchAll();
    }
}
