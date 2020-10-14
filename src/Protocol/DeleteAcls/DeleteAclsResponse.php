<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteAcls;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class DeleteAclsResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The results for each filter.
     *
     * @var DeleteAclsFilterResult[]
     */
    protected $filterResults = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('filterResults', DeleteAclsFilterResult::class, true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 31;
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

    /**
     * @return DeleteAclsFilterResult[]
     */
    public function getFilterResults(): array
    {
        return $this->filterResults;
    }

    /**
     * @param DeleteAclsFilterResult[] $filterResults
     */
    public function setFilterResults(array $filterResults): self
    {
        $this->filterResults = $filterResults;

        return $this;
    }
}
