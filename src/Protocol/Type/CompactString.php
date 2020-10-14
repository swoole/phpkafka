<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class CompactString extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(string $value): string
    {
        return UVarInt::pack(\strlen($value) + 1) . $value;
    }

    public static function unpack(string $value, ?int &$size = null): string
    {
        $length = UVarInt::unpack($value, $size) - 1;
        $result = substr($value, $size, $length);
        $size += $length;

        return $result;
    }
}
