<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

use InvalidArgumentException;
use longlang\phpkafka\Protocol\ProtocolUtil;

class Int16 extends AbstractType
{
    public const FORAMT = 's';

    public const MIN_VALUE = -32768;

    public const MAX_VALUE = 32767;

    private function __construct()
    {
    }

    public static function pack(int $value): string
    {
        if ($value < self::MIN_VALUE || $value > self::MAX_VALUE) {
            throw new InvalidArgumentException(sprintf('%s is outside the range of Int16', $value));
        }
        $result = pack(self::FORAMT, $value);
        if (!ProtocolUtil::nativeIsBigEndian()) {
            $result = strrev($result);
        }

        return $result;
    }

    public static function unpack(string $value, ?int &$size = null): int
    {
        $value = substr($value, 0, 2);
        if (!ProtocolUtil::nativeIsBigEndian()) {
            $value = strrev($value);
        }
        $size = 2;

        return unpack(self::FORAMT, $value)[1];
    }
}
