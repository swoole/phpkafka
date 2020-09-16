<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

class ProtocolUtil
{
    /**
     * platform is big endian.
     *
     * @var bool
     */
    private static $nativeIsBigEndian;

    public static function nativeIsBigEndian(): bool
    {
        if (null === self::$nativeIsBigEndian) {
            self::$nativeIsBigEndian = pack('L', 1) === pack('N', 1);
        }

        return self::$nativeIsBigEndian;
    }
}
