<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch;

use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;

/**
 * 	bit 0~2:
 *      0: no compression
 *      1: gzip
 *      2: snappy
 *      3: lz4
 *      4: zstd
 *  bit 3: timestampType
 *  bit 4: isTransactional (0 means not transactional)
 *  bit 5: isControlBatch (0 means not a control batch)
 *  bit 6~15: unused.
 */
class Attributes
{
    /**
     * compression.
     *
     * 0: no compression
     * 1: gzip
     * 2: snappy
     * 3: lz4
     * 4: zstd
     *
     * @var int
     */
    protected $compression;

    /**
     * @var int
     */
    protected $timestampType;

    /**
     * @var bool
     */
    protected $isTransactional;

    /**
     * @var bool
     */
    protected $isControlBatch;

    public function __construct(int $value = 0)
    {
        $this->setValue($value);
    }

    public function setValue(int $value): void
    {
        $binString = (str_pad(decbin($value), 16, '0', \STR_PAD_LEFT));

        $this->setIsControlBatch('1' === $binString[10]);
        $this->setIsTransactional('1' === $binString[11]);
        $this->setTimestampType((int) $binString[12]);
        $this->setCompression((int) bindec(substr($binString, 13, 3)));
    }

    public function getValue(): int
    {
        $binString = str_repeat('0', 10)
        . decbin((int) $this->getIsControlBatch())
        . decbin((int) $this->getIsTransactional())
        . decbin(min($this->getTimestampType(), 1))
        . str_pad(decbin(min(Compression::ZSTD, $this->getCompression())), 3, '0', \STR_PAD_LEFT);

        return bindec($binString);
    }

    public function getCompression(): int
    {
        return $this->compression;
    }

    public function setCompression(int $compression): self
    {
        $this->compression = $compression;

        return $this;
    }

    public function getTimestampType(): int
    {
        return $this->timestampType;
    }

    public function setTimestampType(int $timestampType): self
    {
        $this->timestampType = $timestampType;

        return $this;
    }

    public function getIsTransactional(): bool
    {
        return $this->isTransactional;
    }

    public function setIsTransactional(bool $isTransactional): self
    {
        $this->isTransactional = $isTransactional;

        return $this;
    }

    public function getIsControlBatch(): bool
    {
        return $this->isControlBatch;
    }

    public function setIsControlBatch(bool $isControlBatch): self
    {
        $this->isControlBatch = $isControlBatch;

        return $this;
    }
}
