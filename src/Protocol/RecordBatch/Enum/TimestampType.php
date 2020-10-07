<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\RecordBatch\Enum;

class TimestampType
{
    public const CREATE_TIME = 0;

    public const LOG_APPEND_TIME = 1;

    private function __construct()
    {
    }
}
