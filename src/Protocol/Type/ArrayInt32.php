<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

use InvalidArgumentException;
use longlang\phpkafka\Protocol\AbstractStruct;

class ArrayInt32 extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(?array $array, ?string $elementType = null, int $apiVersion = 0): string
    {
        if (null === $array) {
            $result = Int32::pack(-1);
        } else {
            $length = \count($array);
            $result = Int32::pack($length);
            foreach ($array as $item) {
                if (null === $elementType) {
                    if ($item instanceof AbstractStruct) {
                        $result .= $item->pack($apiVersion);
                    } else {
                        throw new InvalidArgumentException('Unrecognized element type in array');
                    }
                } else {
                    if (is_subclass_of($elementType, AbstractStruct::class)) {
                        $result .= $item->pack($apiVersion);
                        continue;
                    }
                    if (is_subclass_of($elementType, AbstractType::class)) {
                        $result .= $elementType::pack($item);
                        continue;
                    }
                    throw new InvalidArgumentException(sprintf('Invalid type %s', $elementType));
                }
            }
        }

        return $result;
    }

    public static function unpack(string $value, ?int &$size, string $elementType, int $apiVersion = 0): ?array
    {
        $length = Int32::unpack($value, $tmpSize);
        if (-1 === $length) {
            $array = null;
            $size = $tmpSize;
        } else {
            $array = [];
            if ($length > 0) {
                $size = 0;
                for ($i = 0; $i < $length; ++$i) {
                    $size += $tmpSize;
                    $value = substr($value, $tmpSize);
                    if (is_subclass_of($elementType, AbstractStruct::class)) {
                        /** @var AbstractStruct $item */
                        $item = $array[] = new $elementType();
                        $item->unpack($value, $tmpSize, $apiVersion);
                        continue;
                    }
                    if (is_subclass_of($elementType, AbstractType::class)) {
                        $array[] = $elementType::unpack($value, $tmpSize);
                        continue;
                    }

                    throw new InvalidArgumentException(sprintf('Invalid type %s', $elementType));
                }
                $size += $tmpSize;
            } else {
                // 0 === length
                $size = $tmpSize;
            }
        }

        return $array;
    }
}
