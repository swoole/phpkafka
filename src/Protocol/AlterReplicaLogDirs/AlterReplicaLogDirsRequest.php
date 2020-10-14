<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterReplicaLogDirs;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterReplicaLogDirsRequest extends AbstractRequest
{
    /**
     * The alterations to make for each directory.
     *
     * @var AlterReplicaLogDir[]
     */
    protected $dirs = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('dirs', AlterReplicaLogDir::class, true, [0, 1], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 34;
    }

    public function getMaxSupportedVersion(): int
    {
        return 1;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return AlterReplicaLogDir[]
     */
    public function getDirs(): array
    {
        return $this->dirs;
    }

    /**
     * @param AlterReplicaLogDir[] $dirs
     */
    public function setDirs(array $dirs): self
    {
        $this->dirs = $dirs;

        return $this;
    }
}
