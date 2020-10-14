<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch;

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

    public function setValue(int $value)
    {
        $binString = strrev(str_pad(decbin($value), 8, '0', \STR_PAD_LEFT));
        $this->setCompression((int) bindec(substr($binString, 0, 3)));
        $this->setTimestampType((int) $binString[3]);
        $this->setIsTransactional('1' === $binString[4]);
        $this->setIsControlBatch('1' === $binString[5]);
    }

    public function getValue(): int
    {
        $binString = strrev(
                    str_pad(decbin($this->getCompression()), 3, '0', \STR_PAD_LEFT)
                    . $this->getTimestampType()
                    . decbin((int) $this->getIsTransactional())
                    . decbin((int) $this->getIsControlBatch())
                    . str_repeat('0', 10)
        );

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
