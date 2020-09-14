<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use Longyan\Kafka\Protocol\ProtocolUtil;

final class VarLong extends AbstractType
{
    const MAX_VARINT_BYTES = 10;

    private function __construct()
    {
    }

    /**
     * @param int $value
     */
    public static function pack($value): string
    {
        $buffer = str_repeat("\0", self::size($value, true));
        $current = 0;

        $high = 0;
        $low = 0;
        if (\PHP_INT_SIZE == 4) {
            ProtocolUtil::divideInt64ToInt32($value, $high, $low, false);
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

    /**
     * @return int
     */
    public static function unpack(string $value, ?int &$size = null)
    {
        $current = 0;
        $length = \strlen($value);

        if (\PHP_INT_SIZE == 4) {
            $high = 0;
            $low = 0;
            $b = 0;

            do {
                if ($current === $length || self::MAX_VARINT_BYTES === $current) {
                    throw new \InvalidArgumentException('Buffer cannot be converted to VarLong');
                }
                $b = \ord($value[$current]);
                $bits = 7 * $current;
                if ($bits >= 32) {
                    $high |= (($b & 0x7F) << ($bits - 32));
                } elseif ($bits > 25) {
                    // $bits is 28 in this case.
                    $low |= (($b & 0x7F) << 28);
                    $high = ($b & 0x7F) >> 4;
                } else {
                    $low |= (($b & 0x7F) << $bits);
                }

                ++$current;
            } while ($b & 0x80);

            $result = ProtocolUtil::combineInt32ToInt64($high, $low);
            if (bccomp($result, 0) < 0) {
                $result = bcadd($result, '18446744073709551616');
            }
        } else {
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
        }

        $size = $current;

        return $result;
    }

    public static function size($value, bool $signExtended = false): int
    {
        if (\PHP_INT_SIZE == 4) {
            if (bccomp($value, 0) < 0 ||
                bccomp($value, '9223372036854775807') > 0) {
                return 10;
            }
            if (bccomp($value, 1 << 7) < 0) {
                return 1;
            }
            if (bccomp($value, 1 << 14) < 0) {
                return 2;
            }
            if (bccomp($value, 1 << 21) < 0) {
                return 3;
            }
            if (bccomp($value, 1 << 28) < 0) {
                return 4;
            }
            if (bccomp($value, '34359738368') < 0) {
                return 5;
            }
            if (bccomp($value, '4398046511104') < 0) {
                return 6;
            }
            if (bccomp($value, '562949953421312') < 0) {
                return 7;
            }
            if (bccomp($value, '72057594037927936') < 0) {
                return 8;
            }

            return 9;
        } else {
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
}
