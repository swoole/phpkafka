<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

use InvalidArgumentException;
use longlang\phpkafka\Protocol\ProtocolUtil;

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
        $buffer = '';
        while (0 != ($value & 0xffffff80)) {
            $b = \chr(($value & 0x7f) | 0x80);
            $buffer .= $b;
            $value = ProtocolUtil::shr32($value, 7);
        }
        $buffer .= \chr($value);

        return $buffer;
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        $result = 0;
        $i = 0;
        $size = 0;
        while (0 != (($b = \ord($value[$size++])) & 0x80)) {
            $result |= ($b & 0x7f) << $i;
            $i += 7;
            if ($i > 28) {
                throw new \InvalidArgumentException('illegal Varint');
            }
        }
        $result |= $b << $i;

        return $result;
    }

    public static function size(int $value): int
    {
        if (\PHP_INT_SIZE == 4 && $value < 0) {
            return 5;
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
