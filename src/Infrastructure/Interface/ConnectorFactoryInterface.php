<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Interface;

interface ConnectorFactoryInterface
{
    public function create(
        string $host,
        int $port = 6379,
        ?string $password = null,
        ?int $dbIndex = null,
        float $timeout = 2.0
    ): ConnectorInterface;
} 