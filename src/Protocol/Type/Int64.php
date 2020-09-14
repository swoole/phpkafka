<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use Longyan\Kafka\Protocol\ProtocolUtil;

final class Int64 extends AbstractType
{
    public const FORAMT = 'q';

    private function __construct()
    {
    }

    public static function pack($value): string
    {
        $result = pack(self::FORAMT, $value);
        if (!ProtocolUtil::nativeIsBigEndian()) {
            $result = strrev($result);
        }

        return $result;
    }

    public static function unpack(string $value, ?int &$size = null)
    {
        if (!ProtocolUtil::nativeIsBigEndian()) {
            $value = strrev($value);
        }
        $size = 8;

        return unpack(self::FORAMT, $value)[1];
    }
}
