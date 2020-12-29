<?php

declare(strict_types=1);

namespace longlang\phpkafka\Util;

class ArrayUtil
{
    private function __construct()
    {
    }

    public static function indexOfSubList(array $source, array $target): int
    {
        if (!$source || !$target) {
            return -1;
        }
        $sourceSize = \count($source);
        $targetSize = \count($target);
        $maxCandidate = $sourceSize - $targetSize;

        for ($i = 0; $i <= $maxCandidate; ++$i) {
            $targetFirstValue = reset($target);
            next($target);
            $sourceValue = current($source);
            next($source);
            if ($sourceValue !== $targetFirstValue) {
                continue;
            }
            for ($j = 1; $j < $targetSize; ++$j) {
                $sourceValue = current($source);
                next($source);
                $targetValue = current($target);
                next($target);
                if ($sourceValue !== $targetValue) {
                    for ($k = 0; $k < $j; ++$k) {
                        prev($source);
                    }
                    continue 2;
                }
            }

            return $i;
        }

        return -1;
    }
}
