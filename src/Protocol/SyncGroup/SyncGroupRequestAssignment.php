<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\SyncGroup;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class SyncGroupRequestAssignment extends AbstractStruct
{
    /**
     * The ID of the member to assign.
     *
     * @var string
     */
    protected $memberId = '';

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
                new ProtocolField('memberId', 'string', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
                new ProtocolField('assignment', 'bytes', false, [0, 1, 2, 3, 4, 5], [4, 5], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [4, 5];
    }

    public function getMemberId(): string
    {
        return $this->memberId;
    }

    public function setMemberId(string $memberId): self
    {
        $this->memberId = $memberId;

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
