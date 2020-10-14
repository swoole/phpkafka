<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch\Enum;

class Compression
{
    public const NONE = 0;

    public const GZIP = 1;

    public const SNAPPY = 2;

    public const LZ4 = 3;

    public const ZSTD = 4;

    private function __construct()
    {
    }
}
