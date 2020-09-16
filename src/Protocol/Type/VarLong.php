<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use InvalidArgumentException;

class VarLong extends AbstractType
{
    public const MAX_VARINT_BYTES = 10;

    public const MIN_VALUE = \PHP_INT_MIN;

    public const MAX_VALUE = \PHP_INT_MAX;

    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        if ($value < self::MIN_VALUE || $value > self::MAX_VALUE) {
            throw new InvalidArgumentException(sprintf('%s is outside the range of Int64', $value));
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
        $current = 0;
        $length = \strlen($value);

        $result = 0;
        $shift = 0;

        do {
            if ($current === $length || self::MAX_VARINT_BYTES === $current) {
                throw new \InvalidArgumentException('Buffer cannot be converted to VarLong');
            }

            $byte = \ord($value[$current]);
            $result |= ($byte & 0x7f) << $shift;
            $shift += 7;
            ++$current;
        } while ($byte > 0x7f);

        $size = $current;

        return $result;
    }

    public static function size($value, bool $signExtended = false): int
    {
        if ($value < 0) {
            return 10;
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
        if ($value < (1 << 35)) {
            return 5;
        }
        if ($value < (1 << 42)) {
            return 6;
        }
        if ($value < (1 << 49)) {
            return 7;
        }
        if ($value < (1 << 56)) {
            return 8;
        }

        return 9;
    }
}
