<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use InvalidArgumentException;
use Longyan\Kafka\Protocol\AbstractStruct;

class CompactArray extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(array $array, ?string $elementType = null, int $apiVersion = 0): string
    {
        $length = \count($array) + 1;
        $result = UVarInt::pack($length);
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
                $typeClass = '\Longyan\Kafka\Protocol\Type\\' . $elementType;
                if (class_exists($typeClass)) {
                    $result .= $typeClass::pack($item);
                    continue;
                }
                throw new InvalidArgumentException(sprintf('Invalid type %s', $elementType));
            }
        }

        return $result;
    }

    public static function unpack(string $value, ?int &$size, string $elementType, int $apiVersion = 0): array
    {
        $array = [];
        $length = UVarInt::unpack($value, $tmpSize) - 1;
        if ($length > 0) {
            $size = 0;
            for ($i = 0; $i < $length; ++$i) {
                $size += $tmpSize;
                $value = substr($value, $tmpSize);
                if (is_subclass_of($elementType, AbstractStruct::class)) {
                    /* @var AbstractStruct $item */
                    $array[] = $item = new $elementType();
                    $item->unpack($value, $tmpSize, $apiVersion);
                    continue;
                }
                $typeClass = '\Longyan\Kafka\Protocol\Type\\' . $elementType;
                if (class_exists($typeClass)) {
                    $array[] = $typeClass::unpack($value, $tmpSize);
                    continue;
                }

                throw new InvalidArgumentException(sprintf('Invalid type %s', $elementType));
            }
            $size += $tmpSize;
        } else {
            $size = $tmpSize;
        }

        return $array;
    }
}
