<?php

declare(strict_types=1);

namespace longlang\phpkafka\Group;

class ProtocolType
{
    public const CONSUMER = 'consumer';

    public const CONNECT = 'connect';

    private function __construct()
    {
    }
}
