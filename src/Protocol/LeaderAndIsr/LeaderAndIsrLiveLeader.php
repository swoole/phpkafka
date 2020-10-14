<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\LeaderAndIsr;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class LeaderAndIsrLiveLeader extends AbstractStruct
{
    /**
     * The leader's broker ID.
     *
     * @var int
     */
    protected $brokerId = 0;

    /**
     * The leader's hostname.
     *
     * @var string
     */
    protected $hostName = '';

    /**
     * The leader's port.
     *
     * @var int
     */
    protected $port = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('brokerId', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('hostName', 'string', false, [0, 1, 2, 3, 4], [4], [], [], null),
                new ProtocolField('port', 'int32', false, [0, 1, 2, 3, 4], [4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [4];
    }

    public function getBrokerId(): int
    {
        return $this->brokerId;
    }

    public function setBrokerId(int $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }

    public function getHostName(): string
    {
        return $this->hostName;
    }

    public function setHostName(string $hostName): self
    {
        $this->hostName = $hostName;

        return $this;
    }

    public function getPort(): int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }
}
