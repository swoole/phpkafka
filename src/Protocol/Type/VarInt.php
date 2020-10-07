<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use Longyan\Kafka\Protocol\ProtocolUtil;

class VarInt extends AbstractType
{
    public const MIN_VALUE = -2147483648;

    public const MAX_VALUE = 2147483647;

    public static function pack(int $value): string
    {
        return UVarInt::pack(($value << 1) ^ ($value >> 31));
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        $result = UVarInt::unpack($value, $size);

        return ProtocolUtil::shr32($result, 1) ^ -($result & 1);
    }

    private function __construct()
    {
    }
}
