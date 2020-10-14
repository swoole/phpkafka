<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch\Enum;

class TimestampType
{
    public const CREATE_TIME = 0;

    public const LOG_APPEND_TIME = 1;

    private function __construct()
    {
    }
}
