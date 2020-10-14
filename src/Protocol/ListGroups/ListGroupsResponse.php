<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ListGroups;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class ListGroupsResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * Each group in the response.
     *
     * @var ListedGroup[]
     */
    protected $groups = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [1, 2, 3, 4], [3, 4], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4], [3, 4], [], [], null),
                new ProtocolField('groups', ListedGroup::class, true, [0, 1, 2, 3, 4], [3, 4], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 16;
    }

    public function getFlexibleVersions(): array
    {
        return [3, 4];
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
     * @return ListedGroup[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    /**
     * @param ListedGroup[] $groups
     */
    public function setGroups(array $groups): self
    {
        $this->groups = $groups;

        return $this;
    }
}
