<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class String16 extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(string $value): string
    {
        return Int16::pack(\strlen($value)) . $value;
    }

    public static function unpack(string $value, ?int &$size = null): string
    {
        $length = Int16::unpack(substr($value, 0, 2), $size);
        $result = substr($value, $size, $length);
        $size += $length;

        return $result;
    }
}
