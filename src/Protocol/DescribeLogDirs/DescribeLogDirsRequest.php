<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeLogDirs;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeLogDirsRequest extends AbstractRequest
{
    /**
     * Each topic that we want to describe log directories for, or null for all topics.
     *
     * @var DescribableLogDirTopic[]|null
     */
    protected $topics = null;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('topics', DescribableLogDirTopic::class, true, [0, 1, 2], [2], [0, 1, 2], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 35;
    }

    public function getMaxSupportedVersion(): int
    {
        return 2;
    }

    public function getFlexibleVersions(): array
    {
        return [2];
    }

    /**
     * @return DescribableLogDirTopic[]|null
     */
    public function getTopics(): ?array
    {
        return $this->topics;
    }

    /**
     * @param DescribableLogDirTopic[]|null $topics
     */
    public function setTopics(?array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
