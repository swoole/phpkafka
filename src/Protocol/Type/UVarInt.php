<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use InvalidArgumentException;

class UVarInt extends AbstractType
{
    public const MIN_VALUE = 0;

    public const MAX_VALUE = 4294967295;

    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        if ($value < self::MIN_VALUE || $value > self::MAX_VALUE) {
            throw new InvalidArgumentException(sprintf('%s is outside the range of VarInt', $value));
        }
        $buffer = str_repeat("\0", self::size($value, true));
        $current = 0;

        $high = 0;
        $low = $value;

        while (($low >= 0x80 || $low < 0) || 0 != $high) {
            $buffer[$current] = \chr($low | 0x80);
            $value = ($value >> 7) & ~(0x7F << ((\PHP_INT_SIZE << 3) - 7));
            $carry = ($high & 0x7F) << ((\PHP_INT_SIZE << 3) - 7);
            $high = ($high >> 7) & ~(0x7F << ((\PHP_INT_SIZE << 3) - 7));
            $low = (($low >> 7) & ~(0x7F << ((\PHP_INT_SIZE << 3) - 7)) | $carry);
            ++$current;
        }
        $buffer[$current] = \chr($low);

        return $buffer;
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        return VarLong::unpack($value, $size) & 0xFFFFFFFF;
    }

    public static function size(int $value, bool $signExtended = false): int
    {
        if ($value < 0) {
            if ($signExtended) {
                return 10;
            } else {
                return 5;
            }
        }
        if ($value < (1 << 7)) {
            return 1;
        }
        if ($value < (1 << 14)) {
            return 2;
        }
        if ($value < (1 << 21)) {
            return 3;
        }
        if ($value < (1 << 28)) {
            return 4;
        }

        return 5;
    }
}
