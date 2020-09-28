<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\CreatePartitions;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class CreatePartitionsAssignment extends AbstractStruct
{
    /**
     * The assigned broker IDs.
     *
     * @var int32[]
     */
    protected $brokerId = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('brokerId', 'int32', true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    /**
     * @return int32[]
     */
    public function getBrokerId(): array
    {
        return $this->brokerId;
    }

    /**
     * @param int32[] $brokerId
     */
    public function setBrokerId(array $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }
}
