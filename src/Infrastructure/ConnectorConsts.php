<?php

declare(strict_types = 1);

namespace Raketa\BackendTestTask\Const;

class ConnectorConsts
{
    public const DEFAULT_RETRY_INTERVAL = 100; 
    public const MAX_RETRIES = 3;
    public const DEFAULT_TIMEOUT = 2.0;
    public const CACHE_TTL = 86400;
}
