<?php

namespace Raketa\BackendTestTask\Infrastructure\Interface;

interface ConnectorInterface
{
    public function get(string $key): mixed;
    public function set(string $key, mixed $value): void;
    public function has(string $key): bool;
}

