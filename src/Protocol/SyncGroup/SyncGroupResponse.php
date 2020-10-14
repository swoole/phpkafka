<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SyncGroup;

use longlang\phpkafka\Protocol\AbstractResponse;
use longlang\phpkafka\Protocol\ProtocolField;

class SyncGroupResponse extends AbstractResponse
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
     * The group protocol type.
     *
     * @var string|null
     */
    protected $protocolType = null;

    /**
     * The group protocol name.
     *
     * @var string|null
     */
    protected $protocolName = null;

    /**
     * The member assignment.
     *
     * @var string
     */
    protected $assignment = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('throttleTimeMs', 'int32', false, [1, 2, 3, 4, 5], [4, 5], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
                new ProtocolField('protocolType', 'string', false, [5], [4, 5], [5], [], null),
                new ProtocolField('protocolName', 'string', false, [5], [4, 5], [5], [], null),
                new ProtocolField('assignment', 'bytes', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 14;
    }

    public function getFlexibleVersions(): array
    {
        return [4, 5];
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

    public function getProtocolType(): ?string
    {
        return $this->protocolType;
    }

    public function setProtocolType(?string $protocolType): self
    {
        $this->protocolType = $protocolType;

        return $this;
    }

    public function getProtocolName(): ?string
    {
        return $this->protocolName;
    }

    public function setProtocolName(?string $protocolName): self
    {
        $this->protocolName = $protocolName;

        return $this;
    }

    public function getAssignment(): string
    {
        return $this->assignment;
    }

    public function setAssignment(string $assignment): self
    {
        $this->assignment = $assignment;

        return $this;
    }
}
