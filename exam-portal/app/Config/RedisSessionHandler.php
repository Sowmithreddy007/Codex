<?php

declare(strict_types=1);

namespace App\Config;

use SessionHandlerInterface;

final class RedisSessionHandler implements SessionHandlerInterface
{
    public function open(string $path, string $name): bool
    {
        return true;
    }

    public function close(): bool
    {
        return true;
    }

    public function read(string $id): string|false
    {
        $data = RedisHandler::instance()->client()->get('sess_' . $id);

        return $data === false ? '' : (string) $data;
    }

    public function write(string $id, string $data): bool
    {
        $ttl = (int) ($_ENV['SESSION_LIFETIME'] ?? getenv('SESSION_LIFETIME') ?: 7200);

        return RedisHandler::instance()->client()->setex('sess_' . $id, $ttl, $data);
    }

    public function destroy(string $id): bool
    {
        RedisHandler::instance()->client()->del('sess_' . $id);

        return true;
    }

    public function gc(int $max_lifetime): int|false
    {
        return 0;
    }
}
