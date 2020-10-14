<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\Type\CompactArray;
use longlang\phpkafka\Protocol\Type\Int8;
use longlang\phpkafka\Protocol\Type\VarInt;

class Record extends AbstractStruct
{
    /**
     * @var int
     */
    protected $length = 0;

    /**
     * @var int
     */
    protected $attributes = 0;

    /**
     * @var int
     */
    protected $timestampDelta = 0;

    /**
     * @var int
     */
    protected $offsetDelta = 0;

    /**
     * @var string|null
     */
    protected $key = null;

    /**
     * @var string|null
     */
    protected $value = null;

    /**
     * @var RecordHeader[]
     */
    protected $headers = [];

    public function __construct()
    {
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function pack(int $apiVersion = 0): string
    {
        $data = '';
        $data .= Int8::pack($this->attributes);
        $data .= VarInt::pack($this->timestampDelta);
        $data .= VarInt::pack($this->offsetDelta);
        if (null === $this->key) {
            $data .= VarInt::pack(-1);
        } else {
            $data .= VarInt::pack(\strlen($this->key)) . $this->key;
        }
        if (null === $this->value) {
            $data .= VarInt::pack(-1);
        } else {
            $data .= VarInt::pack(\strlen($this->value)) . $this->value;
        }
        $data .= VarInt::pack(\count($this->headers));
        foreach ($this->headers as $header) {
            $data .= $header->pack($apiVersion);
        }

        $this->length = $length = \strlen($data);

        return VarInt::pack($length) . $data;
    }

    public function unpack(string $data, ?int &$size = null, int $apiVersion = 0): void
    {
        if ('' === $data) {
            return;
        }
        $size = 0;
        $this->length = VarInt::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);
        $size += $tmpSize;

        $this->attributes = Int8::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);
        $size += $tmpSize;

        $this->timestampDelta = VarInt::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);
        $size += $tmpSize;

        $this->offsetDelta = VarInt::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);
        $size += $tmpSize;

        $len = VarInt::unpack($data, $tmpSize);
        if ($len > 0) {
            $size += $len;
            $this->key = substr($data, $tmpSize, $len);
            $data = substr($data, $tmpSize + $len);
        } else {
            $data = substr($data, $tmpSize);
        }
        $size += $tmpSize;

        $len = VarInt::unpack($data, $tmpSize);
        if ($len > 0) {
            $size += $len;
            $this->value = substr($data, $tmpSize, $len);
            $data = substr($data, $tmpSize + $len);
        } else {
            $data = substr($data, $tmpSize);
        }
        $size += $tmpSize;

        $this->headers = CompactArray::unpack($data, $tmpSize, RecordHeader::class) ?? [];
        $data = substr($data, $tmpSize);
        $size += $tmpSize;
    }

    public function toArray(): array
    {
        $array = [
            'length'         => $this->length,
            'attributes'     => $this->attributes,
            'timestampDelta' => $this->timestampDelta,
            'offsetDelta'    => $this->offsetDelta,
            'key'            => $this->key,
            'value'          => $this->value,
        ];
        $headers = [];
        foreach ($this->headers as $header) {
            $headers[] = $header->toArray();
        }
        $array['headers'] = $headers;

        return $array;
    }

    public function getLength(): int
    {
        return $this->length;
    }

    public function getAttributes(): int
    {
        return $this->attributes;
    }

    public function setAttributes(int $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getTimestampDelta(): int
    {
        return $this->timestampDelta;
    }

    public function setTimestampDelta(int $timestampDelta): self
    {
        $this->timestampDelta = $timestampDelta;

        return $this;
    }

    public function getOffsetDelta(): int
    {
        return $this->offsetDelta;
    }

    public function setOffsetDelta(int $offsetDelta): self
    {
        $this->offsetDelta = $offsetDelta;

        return $this;
    }

    public function getKey(): ?string
    {
        return $this->key;
    }

    public function setKey(?string $key): self
    {
        $this->key = $key;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(?string $value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return RecordHeader[]
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param RecordHeader[] $headers
     */
    public function setHeaders(array $headers): self
    {
        $this->headers = $headers;

        return $this;
    }
}
