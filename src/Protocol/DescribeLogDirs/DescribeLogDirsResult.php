<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeLogDirs;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class DescribeLogDirsResult extends AbstractStruct
{
    /**
     * The error code, or 0 if there was no error.
     *
     * @var int
     */
    protected $errorCode = 0;

    /**
     * The absolute log directory path.
     *
     * @var string
     */
    protected $logDir = '';

    /**
     * Each topic.
     *
     * @var DescribeLogDirsTopic[]
     */
    protected $topics = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('errorCode', 'int16', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('logDir', 'string', false, [0, 1, 2], [2], [], [], null),
                new ProtocolField('topics', DescribeLogDirsTopic::class, true, [0, 1, 2], [2], [], [], null),
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

    public function getLogDir(): string
    {
        return $this->logDir;
    }

    public function setLogDir(string $logDir): self
    {
        $this->logDir = $logDir;

        return $this;
    }

    /**
     * @return DescribeLogDirsTopic[]
     */
    public function getTopics(): array
    {
        return $this->topics;
    }

    /**
     * @param DescribeLogDirsTopic[] $topics
     */
    public function setTopics(array $topics): self
    {
        $this->topics = $topics;

        return $this;
    }
}
