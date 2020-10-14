<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class Float64 extends AbstractType
{
    public const FORAMT = 'E';

    private function __construct()
    {
    }

    public static function pack(float $value): string
    {
        return pack(self::FORAMT, $value);
    }

    public static function unpack(string $value, ?int &$size = null): float
    {
        $size = 8;

        return unpack(self::FORAMT, $value)[1];
    }
}
