<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\Interface\ConnectorInterface;
use Raketa\BackendTestTask\Const\ConnectorConsts;
use Redis;
use RedisException;

readonly class RedisConnector implements ConnectorInterface
{
    public function __construct(
        private Redis $redis
    ) {}

    public function get(string $key): mixed
    {
        try {
            return $this->redis->get($key);
        } catch (RedisException $e) {
            throw new ConnectorException('Failed to get data from Redis', $e->getCode(), $e);
        }
    }

    public function set(string $key, mixed $value): void
    {
        try {
            $this->redis->setex($key, ConnectorConsts::CACHE_TTL, $value);
        } catch (RedisException $e) {
            throw new ConnectorException(
                sprintf('Failed to store data in Redis for key "%s"', $key),
                $e->getCode(),
                $e
            );
        }
    }

    public function has(string $key): bool
    {
        try {
            return (bool)$this->redis->exists($key);
        } catch (RedisException) {
            return false;
        }
    }
}
