<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ElectLeaders;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class ElectLeadersResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The top level response error code.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The election results, or an empty array if the requester did not have permission and the request asks for all partitions.
     *
     * @var ReplicaElectionResult[]
     */
    protected $replicaElectionResults = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [1, 2], [2], [], [], null),
                new ProtocolField('replicaElectionResults', ReplicaElectionResult::class, true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 43;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getThrottleTimeMs(): int
    {
        return $this->throttleTimeMs;
    }

    public function setThrottleTimeMs(int $throttleTimeMs): self
    {
        $this->throttleTimeMs = $throttleTimeMs;

        return $this;
    }

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * @return ReplicaElectionResult[]
     */
    public function getReplicaElectionResults(): array
    {
        return $this->replicaElectionResults;
    }

    /**
     * @param ReplicaElectionResult[] $replicaElectionResults
     */
    public function setReplicaElectionResults(array $replicaElectionResults): self
    {
        $this->replicaElectionResults = $replicaElectionResults;

        return $this;
    }
}
