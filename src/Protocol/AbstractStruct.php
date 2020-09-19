<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

abstract class AbstractStruct implements \JsonSerializable
{
    /**
     * Protocol fields map.
     *
     * @var ProtocolField[]
     */
    protected $map;

    public function pack(int $apiVersion = 0): string
    {
        $result = '';
        foreach ($this->map as $fieldName => $protocolField) {
            if ($apiVersion < $protocolField->getVersion()) {
                continue;
            }
            $value = $this->$fieldName;
            $arrayType = $protocolField->getArrayType();
            if (null === $arrayType) {
                if ($value instanceof self) {
                    $result .= $value->pack($apiVersion);
                    continue;
                }
                $typeClass = '\Longyan\Kafka\Protocol\Type\\' . $protocolField->getType();
                if (class_exists($typeClass)) {
                    $result .= $typeClass::pack($value);
                    continue;
                }
                throw new \InvalidArgumentException(sprintf('Invalid type %s', $protocolField->getTypeForDisplay()));
            } else {
                $arrayTypeClass = '\Longyan\Kafka\Protocol\Type\\' . $arrayType;
                if (!class_exists($arrayTypeClass)) {
                    throw new \InvalidArgumentException(sprintf('Invalid arrayType %s', $arrayType));
                }
                $result .= $arrayTypeClass::pack($value, $protocolField->getType());
            }
        }

        return $result;
    }

    public function unpack(string $data, ?int &$size = null, int $apiVersion = 0): void
    {
        $size = $tmpSize = 0;
        foreach ($this->map as $fieldName => $protocolField) {
            if ($apiVersion < $protocolField->getVersion()) {
                continue;
            }
            $type = $protocolField->getType();
            $arrayType = $protocolField->getArrayType();
            if (null === $arrayType) {
                $typeClass = '\Longyan\Kafka\Protocol\Type\\' . $type;
                if (class_exists($typeClass)) {
                    $this->$fieldName = $typeClass::unpack($data, $tmpSize);
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
                    $this->$fieldName = $value;
                }
            } else {
                $arrayTypeClass = '\Longyan\Kafka\Protocol\Type\\' . $arrayType;
                if (!class_exists($arrayTypeClass)) {
                    throw new \InvalidArgumentException(sprintf('Invalid arrayType %s', $arrayType));
                }
                $this->$fieldName = $arrayTypeClass::unpack($data, $tmpSize, $type);
            }
            $data = substr($data, $tmpSize);
            $size += $tmpSize;
        }
    }

    public function toArray(): array
    {
        $result = [];
        foreach ($this->map as $fieldName => $protocolField) {
            $value = $this->$fieldName;
            if ($value instanceof self) {
                $value = $value->toArray();
            } elseif (null !== $protocolField->getArrayType()) {
                $array = [];
                foreach ($value as $item) {
                    $array[] = $item->toArray();
                }
                $value = $array;
            }
            $result[$fieldName] = $value;
        }

        return $result;
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
