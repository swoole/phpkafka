<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

final class Int8 extends AbstractType
{
    public const FORAMT = 'c';

    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        return pack(self::FORAMT, $value);
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        $size = 1;

        return unpack(self::FORAMT, $value)[1];
    }
}
