<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeClientQuotas;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeClientQuotasResponse extends AbstractResponse
{
    /**
     * The duration in milliseconds for which the request was throttled due to a quota violation, or zero if the request did not violate any quota.
     *
     * @var int
     */
    protected $throttleTimeMs = 0;

    /**
     * The error code, or `0` if the quota description succeeded.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The error message, or `null` if the quota description succeeded.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    /**
     * A result entry.
     *
     * @var EntryData[]|null
     */
    protected $entries = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [0], [], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0], [], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0], [], [0], [], null),
                new ProtocolField('entries', EntryData::class, true, [0], [], [0], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 48;
    }

    public function getFlexibleVersions(): array
    {
        return [];
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

    public function getErrorMessage(): ?string
    {
        return $this->errorMessage;
    }

    public function setErrorMessage(?string $errorMessage): self
    {
        $this->errorMessage = $errorMessage;

        return $this;
    }

    /**
     * @return EntryData[]|null
     */
    public function getEntries(): ?array
    {
        return $this->entries;
    }

    /**
     * @param EntryData[]|null $entries
     */
    public function setEntries(?array $entries): self
    {
        $this->entries = $entries;

        return $this;
    }
}
