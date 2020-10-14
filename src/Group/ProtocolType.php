<?php

declare(strict_types=1);

namespace Longyan\Kafka\Group;

class ProtocolType
{
    public const CONSUMER = 'consumer';

    public const CONNECT = 'connect';

    private function __construct()
    {
    }
}
