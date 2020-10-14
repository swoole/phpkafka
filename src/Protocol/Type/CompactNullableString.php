<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class CompactNullableString extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(?string $value): string
    {
        if (null === $value) {
            $length = 0;
        } else {
            $length = \strlen($value) + 1;
        }

        return UVarInt::pack($length) . $value;
    }

    public static function unpack(string $value, ?int &$size = null): ?string
    {
        $length = UVarInt::unpack($value, $size) - 1;
        if (-1 === $length) {
            return null;
        }
        $result = substr($value, $size, $length);
        $size += $length;

        return $result;
    }
}
