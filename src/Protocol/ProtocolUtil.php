<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

final class ProtocolUtil
{
    /**
     * platform is big endian.
     *
     * @var bool
     */
    private static $nativeIsBigEndian;

    /**
     * @return bool
     */
    public static function nativeIsBigEndian()
    {
        if (null === self::$nativeIsBigEndian) {
            self::$nativeIsBigEndian = pack('L', 1) === pack('N', 1);
        }

        return self::$nativeIsBigEndian;
    }

    public static function divideInt64ToInt32($value, &$high, &$low, bool $trim = false): void
    {
        $isNeg = (bccomp($value, 0) < 0);
        if ($isNeg) {
            $value = bcsub(0, $value);
        }

        $high = bcdiv($value, 4294967296);
        $low = bcmod($value, 4294967296);
        if (bccomp($high, 2147483647) > 0) {
            $high = (int) bcsub($high, 4294967296);
        } else {
            $high = (int) $high;
        }
        if (bccomp($low, 2147483647) > 0) {
            $low = (int) bcsub($low, 4294967296);
        } else {
            $low = (int) $low;
        }

        if ($isNeg) {
            $high = ~$high;
            $low = ~$low;
            ++$low;
            if (!$low) {
                $high = (int) ($high + 1);
            }
        }

        if ($trim) {
            $high = 0;
        }
    }

    public static function combineInt32ToInt64($high, $low)
    {
        $isNeg = $high < 0;
        if ($isNeg) {
            $high = ~$high;
            $low = ~$low;
            ++$low;
            if (!$low) {
                $high = (int) ($high + 1);
            }
        }
        $result = bcadd(bcmul($high, 4294967296), $low);
        if ($low < 0) {
            $result = bcadd($result, 4294967296);
        }
        if ($isNeg) {
            $result = bcsub(0, $result);
        }

        return $result;
    }
}
