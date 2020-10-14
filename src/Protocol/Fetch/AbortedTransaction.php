<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Fetch;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class AbortedTransaction extends AbstractStruct
{
    /**
     * The producer id associated with the aborted transaction.
     *
     * @var int
     */
    protected $producerId = 0;

    /**
     * The first offset in the aborted transaction.
     *
     * @var int
     */
    protected $firstOffset = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('producerId', 'int64', false, [4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
                new ProtocolField('firstOffset', 'int64', false, [4, 5, 6, 7, 8, 9, 10, 11], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getProducerId(): int
    {
        return $this->producerId;
    }

    public function setProducerId(int $producerId): self
    {
        $this->producerId = $producerId;

        return $this;
    }

    public function getFirstOffset(): int
    {
        return $this->firstOffset;
    }

    public function setFirstOffset(int $firstOffset): self
    {
        $this->firstOffset = $firstOffset;

        return $this;
    }
}
