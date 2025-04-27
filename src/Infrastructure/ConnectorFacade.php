<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure;

use Raketa\BackendTestTask\Infrastructure\Interface\ConnectorInterface;
use Raketa\BackendTestTask\Infrastructure\Interface\ConnectorFactoryInterface;
use Raketa\BackendTestTask\Const\ConnectorConsts;
use Raketa\BackendTestTask\Infrastructure\Exception\ConnectorException;
use Exception;

class ConnectorFacade
{
    private readonly string $host;
    private readonly int $port;
    private readonly ?string $password;
    private readonly ?int $dbIndex;
    private readonly float $timeout;
    private readonly ConnectorFactoryInterface $factory;
    private ?ConnectorInterface $connector = null;

    public function __construct(
        string $host,
        int $port = 6379,
        ?string $password = null,
        ?int $dbIndex = null,
        float $timeout = ConnectorConsts::DEFAULT_TIMEOUT,
        string $factoryClass
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->password = $password;
        $this->dbIndex = $dbIndex;
        $this->timeout = $timeout;

        $this->factory = new $factoryClass(
            $this->host,
            $this->port,
            $this->password,
            $this->dbIndex,
            $this->timeout
        );
    }

    public function build(): ConnectorInterface
    {
        try {
            if ($this->connector !== null) {
                return $this->connector;
            }
    
            $this->connector = $this->factory->create(
                $this->host,
                $this->port,
                $this->password,
                $this->dbIndex,
                $this->timeout
            );
    
            return $this->connector;
        } catch (Exception $e) {
            throw new ConnectorException('Failed to build connector', $e->getCode(), $e);
        }
    }

    public function getConnector(): ?ConnectorInterface
    {
        return $this->connector;
    }
}
