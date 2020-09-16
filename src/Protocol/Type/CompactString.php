<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

class CompactString extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(string $value): string
    {
        return VarInt::pack(\strlen($value)) . $value;
    }

    public static function unpack(string $value, ?int &$size = null): string
    {
        $length = VarInt::unpack($value, $size);
        $result = substr($value, $size, $length);
        $size += $length;

        return $result;
    }
}
