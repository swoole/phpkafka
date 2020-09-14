<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use Longyan\Kafka\Protocol\ProtocolUtil;

final class VarInt extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        $buffer = str_repeat("\0", self::size($value, true));
        $current = 0;

        $high = 0;
        $low = 0;
        if (\PHP_INT_SIZE == 4) {
            ProtocolUtil::divideInt64ToInt32($value, $high, $low, true);
        } else {
            $low = $value;
        }

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
        $intValue = VarLong::unpack($value, $size);
        if (\PHP_INT_SIZE == 4) {
            $intValue = bcmod($intValue, 4294967296);
        } else {
            $intValue &= 0xFFFFFFFF;
        }

        // Convert large uint32 to int32.
        if ($intValue > 0x7FFFFFFF) {
            if (\PHP_INT_SIZE === 8) {
                $intValue = $intValue | (0xFFFFFFFF << 32);
            } else {
                $intValue = bcsub($intValue, 4294967296);
            }
        }

        return (int) $intValue;
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
