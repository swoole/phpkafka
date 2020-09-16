<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\Type;

use InvalidArgumentException;
use Longyan\Kafka\Protocol\AbstractProtocol;

class ArrayInt32 extends AbstractType
{
    private function __construct()
    {
    }

    public static function pack(array $array, ?string $elementType = null): string
    {
        $length = \count($array);
        $result = Int32::pack($length);
        foreach ($array as $item) {
            if (null === $elementType) {
                if ($item instanceof AbstractProtocol) {
                    $result .= $item->pack();
                } else {
                    throw new InvalidArgumentException('Unrecognized element type in array');
                }
            } else {
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

    public static function unpack(string $value, ?int &$size, string $elementType): array
    {
        $array = [];
        $length = Int32::unpack($value, $tmpSize);
        if ($length > 0) {
            $size = 0;
            for ($i = 0; $i < $length; ++$i) {
                $size += $tmpSize;
                $value = substr($value, $tmpSize);
                if (is_subclass_of($elementType, AbstractProtocol::class)) {
                    /** @var AbstractProtocol $item */
                    $item = new $elementType();
                    $item->unpack($value, $tmpSize);
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
