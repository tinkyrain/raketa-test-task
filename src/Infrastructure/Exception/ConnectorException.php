<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Infrastructure\Exception;

use Throwable;
use Exception;

class ConnectorException extends Exception
{
    public function __construct(
        private string $message,
        private int $code,
        private ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public function __toString(): string
    {
        return sprintf(
            '[%s] %s in %s on line %d',
            $this->getCode(),
            $this->getMessage(),
            $this->getFile(),
            $this->getLine(),
        );
    }
}
