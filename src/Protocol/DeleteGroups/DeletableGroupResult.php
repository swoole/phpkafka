<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteGroups;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DeletableGroupResult extends AbstractStruct
{
    /**
     * The group id.
     *
     * @var string
     */
    protected $groupId = '';

    /**
     * The deletion error, or 0 if the deletion succeeded.
     *
     * @var int
     */
    protected $errorCode = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('groupId', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    public function getGroupId(): string
    {
        return $this->groupId;
    }

    public function setGroupId(string $groupId): self
    {
        $this->groupId = $groupId;

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
}
