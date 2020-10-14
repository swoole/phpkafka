<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ApiVersions;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class ApiVersionsResponseKey extends AbstractStruct
{
    /**
     * The API index.
     *
     * @var int
     */
    protected $apiKey = 0;

    /**
     * The minimum supported version, inclusive.
     *
     * @var int
     */
    protected $minVersion = 0;

    /**
     * The maximum supported version, inclusive.
     *
     * @var int
     */
    protected $maxVersion = 0;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('apiKey', 'int16', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('minVersion', 'int16', false, [0, 1, 2, 3], [3], [], [], null),
                new ProtocolField('maxVersion', 'int16', false, [0, 1, 2, 3], [3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [3];
    }

    public function getApiKey(): int
    {
        return $this->apiKey;
    }

    public function setApiKey(int $apiKey): self
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    public function getMinVersion(): int
    {
        return $this->minVersion;
    }

    public function setMinVersion(int $minVersion): self
    {
        $this->minVersion = $minVersion;

        return $this;
    }

    public function getMaxVersion(): int
    {
        return $this->maxVersion;
    }

    public function setMaxVersion(int $maxVersion): self
    {
        $this->maxVersion = $maxVersion;

        return $this;
    }
}
