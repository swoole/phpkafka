<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class Boolean extends AbstractType
{
    public const FORAMT = 'c';

    private function __construct()
    {
    }

    public static function pack(bool $value): string
    {
        return pack(self::FORAMT, $value);
    }

    public static function unpack(string $value, ?int &$size = null): bool
    {
        $size = 1;

        return 1 === unpack(self::FORAMT, $value)[1];
    }
}
