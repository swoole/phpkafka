<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

use InvalidArgumentException;

class Int8 extends AbstractType
{
    public const FORAMT = 'c';

    public const MIN_VALUE = -128;

    public const MAX_VALUE = 127;

    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        if ($value < self::MIN_VALUE || $value > self::MAX_VALUE) {
            throw new InvalidArgumentException(sprintf('%s is outside the range of Int8', $value));
        }

        return pack(self::FORAMT, $value);
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        $size = 1;

        return unpack(self::FORAMT, $value)[1];
    }
}
