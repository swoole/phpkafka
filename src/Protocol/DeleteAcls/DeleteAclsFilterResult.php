<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DeleteAcls;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DeleteAclsFilterResult extends AbstractStruct
{
    /**
     * The error code, or 0 if the filter succeeded.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The error message, or null if the filter succeeded.
     *
     * @var string|null
     */
    protected $errorMessage = null;

    /**
     * The ACLs which matched this filter.
     *
     * @var DeleteAclsMatchingAcl[]
     */
    protected $matchingAcls = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('errorMessage', 'string', false, [0, 1, 2], [2], [0, 1, 2], [], null),
                new ProtocolField('matchingAcls', DeleteAclsMatchingAcl::class, true, [0, 1, 2], [2], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [2];
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
     * @return DeleteAclsMatchingAcl[]
     */
    public function getMatchingAcls(): array
    {
        return $this->matchingAcls;
    }

    /**
     * @param DeleteAclsMatchingAcl[] $matchingAcls
     */
    public function setMatchingAcls(array $matchingAcls): self
    {
        $this->matchingAcls = $matchingAcls;

        return $this;
    }
}
