<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class NullableString extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(?string $value): string
    {
        if (null === $value) {
            $length = -1;
        } else {
            $length = \strlen($value);
        }

        return Int16::pack($length) . $value;
    }

    public static function unpack(string $value, ?int &$size = null): ?string
    {
        $length = Int16::unpack($value, $size);
        if (-1 === $length) {
            return null;
        }
        $result = substr($value, $size, $length);
        $size += $length;

        return $result;
    }
}
