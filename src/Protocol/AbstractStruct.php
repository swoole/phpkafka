<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

use Longyan\Kafka\Protocol\Type\UVarInt;

abstract class AbstractStruct implements \JsonSerializable
{
    /**
     * Protocol fields map.
     *
     * @var ProtocolField[][]
     */
    protected static $maps = [];

    /**
     * tagged fieldses.
     *
     * @var ProtocolField[][]
     */
    protected static $taggedFieldses = [];

    public function pack(int $apiVersion = 0): string
    {
        $parsedfieldsNames = [];
        $result = '';
        $taggedResult = '';
        $hasTagged = false;
        $taggedIndex = 0;
        foreach (array_merge(self::$maps[static::class], ['|'], self::$taggedFieldses[static::class]) as $protocolField) {
            if ('|' === $protocolField) {
                if ($apiVersion < $this->getFlexibleVersions()) {
                    break;
                }
                $hasTagged = true;
                continue;
            }
            /** @var ProtocolField $protocolField */
            if ($apiVersion < $protocolField->getVersion()) {
                continue;
            }
            $fieldName = $protocolField->getName();
            if (isset($parsedfieldsNames[$fieldName])) {
                continue;
            }
            $parsedfieldsNames[$fieldName] = 1;
            $value = $this->$fieldName;
            if ($hasTagged && null === $value) {
                continue;
            }
            $arrayType = $protocolField->getArrayType();
            if (null === $arrayType) {
                if ($value instanceof self) {
                    $item = $value->pack($apiVersion);
                } else {
                    $typeClass = '\Longyan\Kafka\Protocol\Type\\' . $protocolField->getType();
                    if (class_exists($typeClass)) {
                        $item = $typeClass::pack($value);
                    } else {
                        throw new \InvalidArgumentException(sprintf('Invalid type %s', $protocolField->getTypeForDisplay()));
                    }
                }
            } else {
                $arrayTypeClass = '\Longyan\Kafka\Protocol\Type\\' . $arrayType;
                if (!class_exists($arrayTypeClass)) {
                    throw new \InvalidArgumentException(sprintf('Invalid arrayType %s', $arrayType));
                }
                $item = $arrayTypeClass::pack($value, $protocolField->getType(), $apiVersion);
            }
            if ($hasTagged) {
                $item = UVarInt::pack($taggedIndex) . UVarInt::pack(\strlen($item)) . $item;
                $taggedResult .= $item;
                ++$taggedIndex;
            } else {
                $result .= $item;
            }
        }

        $flexibleVersions = $this->getFlexibleVersions();
        if (null !== $flexibleVersions && $apiVersion >= $flexibleVersions) {
            return $result . UVarInt::pack($taggedIndex) . $taggedResult;
        } else {
            return $result;
        }
    }

    public function unpack(string $data, ?int &$size = null, int $apiVersion = 0): void
    {
        $parsedfieldsNames = [];
        $size = $tmpSize = 0;
        foreach (self::$maps[static::class] as $protocolField) {
            if ($apiVersion < $protocolField->getVersion()) {
                continue;
            }
            $fieldName = $protocolField->getName();
            if (isset($parsedfieldsNames[$fieldName])) {
                continue;
            }
            $parsedfieldsNames[$fieldName] = 1;
            $this->$fieldName = $this->unpackItem($apiVersion, $data, $protocolField, $tmpSize);
            $data = substr($data, $tmpSize);
            $size += $tmpSize;
        }
        $flexibleVersions = $this->getFlexibleVersions();
        if (null !== $flexibleVersions && $apiVersion >= $flexibleVersions) {
            $taggedFieldsCount = UVarInt::unpack($data, $tmpSize);
            $data = substr($data, $tmpSize);
            $size += $tmpSize;
            for ($i = 0; $i < $taggedFieldsCount; ++$i) {
                $tag = UVarInt::unpack($data, $tmpSize);
                $data = substr($data, $tmpSize);
                $size += $tmpSize;
                $length = UVarInt::unpack($data, $tmpSize);
                $data = substr($data, $tmpSize);
                $size += $tmpSize + $length;
                $dataBuffer = substr($data, 0, $length);
                $data = substr($data, $length);
                if (!isset(static::$taggedFieldses[static::class][$tag])) {
                    continue;
                }
                $protocolField = static::$taggedFieldses[static::class][$tag];
                $fieldName = $protocolField->getName();
                $this->$fieldName = $this->unpackItem($apiVersion, $dataBuffer, $protocolField, $tmpSize);
            }
        }
    }

    protected function unpackItem(int $apiVersion, string $data, ProtocolField $protocolField, ?int &$tmpSize)
    {
        $type = $protocolField->getType();
        $arrayType = $protocolField->getArrayType();
        if (null === $arrayType) {
            $typeClass = '\Longyan\Kafka\Protocol\Type\\' . $type;
            if (class_exists($typeClass)) {
                $value = $typeClass::unpack($data, $tmpSize);
            } else {
                if (!class_exists($type)) {
                    $className = implode('\\', \array_slice(explode('\\', static::class), 0, -1)) . '\\' . $type;
                    if (!class_exists($className)) {
                        throw new \InvalidArgumentException(sprintf('Invalid type %s', $type));
                    }
                    $type = $className;
                }
                if (!is_subclass_of($type, self::class)) {
                    throw new \InvalidArgumentException(sprintf('Invalid type %s', $type));
                }
                /** @var self $value */
                $value = new $type();
                $value->unpack($data, $tmpSize);
            }
        } else {
            $arrayTypeClass = '\Longyan\Kafka\Protocol\Type\\' . $arrayType;
            if (!class_exists($arrayTypeClass)) {
                throw new \InvalidArgumentException(sprintf('Invalid arrayType %s', $arrayType));
            }
            $value = $arrayTypeClass::unpack($data, $tmpSize, $type, $apiVersion);
        }

        return $value;
    }

    public function toArray(): array
    {
        $parsedfieldsNames = [];
        $result = [];
        foreach (array_merge(self::$maps[static::class], self::$taggedFieldses[static::class]) as $protocolField) {
            $fieldName = $protocolField->getName();
            if (isset($parsedfieldsNames[$fieldName])) {
                continue;
            }
            $parsedfieldsNames[$fieldName] = 1;
            $value = $this->$fieldName;
            if ($value instanceof self) {
                $value = $value->toArray();
            } elseif (null !== $protocolField->getArrayType()) {
                $array = [];
                if ($value) {
                    foreach ($value as $item) {
                        if ($item instanceof self) {
                            $array[] = $item->toArray();
                        } else {
                            $array[] = $item;
                        }
                    }
                }
                $value = $array;
            }
            $result[$fieldName] = $value;
        }

        return $result;
    }

    public function getFlexibleVersions(): ?int
    {
        return null;
    }

    /**
     * json 序列化.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
