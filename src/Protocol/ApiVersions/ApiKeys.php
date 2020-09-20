<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ApiVersions;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ApiKeys extends AbstractStruct
{
    /**
     * The API index.
     *
     * @var int
     */
    protected $apiKey;

    /**
     * The minimum supported version, inclusive.
     *
     * @var int
     */
    protected $minVersion;

    /**
     * The maximum supported version, inclusive.
     *
     * @var int
     */
    protected $maxVersion;

    public function __construct()
    {
        $this->map = [
            new ProtocolField('apiKey', 'Int16', null, 0),
            new ProtocolField('minVersion', 'Int16', null, 0),
            new ProtocolField('maxVersion', 'Int16', null, 0),
        ];
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
