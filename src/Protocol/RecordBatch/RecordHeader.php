<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\Type\VarInt;

class RecordHeader extends AbstractStruct
{
    /**
     * @var string
     */
    protected $headerKey = '';

    /**
     * @var string
     */
    protected $value = '';

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function pack(int $apiVersion = 0): string
    {
        $result = VarInt::pack(\strlen($this->headerKey)) . $this->headerKey . VarInt::pack(\strlen($this->value)) . $this->value;

        return $result;
    }

    public function unpack(string $data, ?int &$size = null, int $apiVersion = 0): void
    {
    }

    public function getHeaderKey(): string
    {
        return $this->headerKey;
    }

    public function setHeaderKey(string $headerKey): self
    {
        $this->headerKey = $headerKey;

        return $this;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
