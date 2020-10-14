<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Metadata;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class MetadataRequest extends AbstractRequest
{
    /**
     * The topics to fetch metadata for.
     *
     * @var MetadataRequestTopic[]|null
     */
    protected $topics = null;

    /**
     * If this is true, the broker may auto-create topics that we requested which do not already exist, if it is configured to do so.
     *
     * @var bool
     */
    protected $allowAutoTopicCreation = true;

    /**
     * Whether to include cluster authorized operations.
     *
     * @var bool
     */
    protected $includeClusterAuthorizedOperations = false;

    /**
     * Whether to include topic authorized operations.
     *
     * @var bool
     */
    protected $includeTopicAuthorizedOperations = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topics', MetadataRequestTopic::class, true, [0, 1, 2, 3, 4, 5, 6, 7, 8, 9], [9], [1, 2, 3, 4, 5, 6, 7, 8, 9], [], null),
                new ProtocolField('allowAutoTopicCreation', 'bool', false, [4, 5, 6, 7, 8, 9], [9], [], [], null),
                new ProtocolField('includeClusterAuthorizedOperations', 'bool', false, [8, 9], [9], [], [], null),
                new ProtocolField('includeTopicAuthorizedOperations', 'bool', false, [8, 9], [9], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 3;
    }

    public function getMaxSupportedVersion(): int
    {
        return 9;
    }

    public function getFlexibleVersions(): array
    {
        return [9];
    }

    /**
     * @return MetadataRequestTopic[]|null
     */
    public function getTopics(): ?array
    {
        return $this->topics;
    }

    /**
     * @param MetadataRequestTopic[]|null $topics
     */
    public function setTopics(?array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }

    public function getAllowAutoTopicCreation(): bool
    {
        return $this->allowAutoTopicCreation;
    }

    public function setAllowAutoTopicCreation(bool $allowAutoTopicCreation): self
    {
        $this->allowAutoTopicCreation = $allowAutoTopicCreation;

        return $this;
    }

    public function getIncludeClusterAuthorizedOperations(): bool
    {
        return $this->includeClusterAuthorizedOperations;
    }

    public function setIncludeClusterAuthorizedOperations(bool $includeClusterAuthorizedOperations): self
    {
        $this->includeClusterAuthorizedOperations = $includeClusterAuthorizedOperations;

        return $this;
    }

    public function getIncludeTopicAuthorizedOperations(): bool
    {
        return $this->includeTopicAuthorizedOperations;
    }

    public function setIncludeTopicAuthorizedOperations(bool $includeTopicAuthorizedOperations): self
    {
        $this->includeTopicAuthorizedOperations = $includeTopicAuthorizedOperations;

        return $this;
    }
}
