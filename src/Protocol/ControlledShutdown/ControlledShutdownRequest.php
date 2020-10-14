<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ControlledShutdown;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class ControlledShutdownRequest extends AbstractRequest
{
    /**
     * The id of the broker for which controlled shutdown has been requested.
     *
     * @var int
     */
    protected $brokerId = 0;

    /**
     * The broker epoch.
     *
     * @var int
     */
    protected $brokerEpoch = -1;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('brokerId', 'int32', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('brokerEpoch', 'int64', false, [2, 3], [3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 7;
    }

    public function getMaxSupportedVersion(): int
    {
        return 3;
    }

    public function getFlexibleVersions(): array
    {
        return [3];
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

    public function getBrokerEpoch(): int
    {
        return $this->brokerEpoch;
    }

    public function setBrokerEpoch(int $brokerEpoch): self
    {
        $this->brokerEpoch = $brokerEpoch;

        return $this;
    }
}
