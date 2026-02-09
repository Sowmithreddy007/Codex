<?php

declare(strict_types=1);

namespace App\Config;

use Redis;
use RedisException;
use RuntimeException;

final class RedisHandler
{
    private static ?self $instance = null;
    private Redis $redis;

    public static function isAvailable(): bool
    {
        return class_exists(Redis::class);
    }

    private function __construct()
    {
        if (!class_exists(Redis::class)) {
            throw new RuntimeException('phpredis extension is required.');
        }

        $host = $_ENV['REDIS_HOST'] ?? getenv('REDIS_HOST') ?: '127.0.0.1';
        $port = (int) ($_ENV['REDIS_PORT'] ?? getenv('REDIS_PORT') ?: 6379);
        $password = $_ENV['REDIS_PASS'] ?? getenv('REDIS_PASS') ?: null;
        $database = (int) ($_ENV['REDIS_DB'] ?? getenv('REDIS_DB') ?: 0);

        $this->redis = new Redis();

        try {
            $this->redis->connect($host, $port, 2.5);

            if (!empty($password)) {
                $this->redis->auth($password);
            }

            $this->redis->select($database);
        } catch (RedisException $exception) {
            throw new RuntimeException('Redis connection failed: ' . $exception->getMessage(), 0, $exception);
        }
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function client(): Redis
    {
        return $this->redis;
    }

    private function __clone()
    {
    }

    public function __wakeup(): void
    {
        throw new RuntimeException('Cannot unserialize singleton');
    }
}
