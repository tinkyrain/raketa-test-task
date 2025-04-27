<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Factory;

use Raketa\BackendTestTask\Infrastructure\RedisConnector;
use Raketa\BackendTestTask\Infrastructure\Interface\ConnectorInterface;
use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Raketa\BackendTestTask\Infrastructure\Interface\ConnectorFactoryInterface;
use Raketa\BackendTestTask\Const\ConnectorConsts;
use Redis;
use RedisException;

class RedisConnectorFactory implements ConnectorFactoryInterface
{
    public function create(
        string $host,
        int $port = 6379,
        ?string $password = null,
        ?int $dbIndex = null,
        float $timeout = ConnectorConsts::DEFAULT_TIMEOUT
    ): ConnectorInterface
    {
        $retries = 0;
        $lastException = null;

        while ($retries < ConnectorConsts::MAX_RETRIES) {
            try {
                $redis = new Redis();
                
                $isConnected = $redis->connect(
                    $host,
                    $port,
                    (float)$timeout
                );

                if (!$isConnected) {
                    throw new ConnectorException(
                        sprintf('Failed to connect to Redis at %s:%d', $host, $port),
                        500
                    );
                }

                if ($password !== null) {
                    $authResult = $redis->auth($password);
                    if (!$authResult) {
                        throw new ConnectorException('Redis authentication failed', 401);
                    }
                }

                if ($dbIndex !== null) {
                    $selectResult = $redis->select($dbIndex);
                    if (!$selectResult) {
                        throw new ConnectorException(
                            sprintf('Failed to select Redis database %d', $dbIndex),
                            500
                        );
                    }
                }

                if (!$redis->ping()) {
                    throw new ConnectorException('Redis ping failed', 500);
                }

                return new RedisConnector($redis);
            } catch (RedisException $e) {
                $lastException = new ConnectorException(
                    'Redis connection error: ' . $e->getMessage(),
                    $e->getCode(),
                    $e
                );
            } catch (ConnectorException $e) {
                $lastException = $e;
            }

            $retries++;
            if ($retries < ConnectorConsts::MAX_RETRIES) {
                usleep(ConnectorConsts::DEFAULT_RETRY_INTERVAL * 1000);
            }
        }

        throw $lastException ?? new ConnectorException(
            'Failed to establish Redis connection after multiple retries',
            500
        );
    }
} 