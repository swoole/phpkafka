<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\ApiVersions;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class ApiVersionsRequest extends AbstractRequest
{
    /**
     * The name of the client.
     *
     * @var string
     */
    protected $clientSoftwareName = '';

    /**
     * The version of the client.
     *
     * @var string
     */
    protected $clientSoftwareVersion = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('clientSoftwareName', 'string', false, [3], [3], [], [], null),
                new ProtocolField('clientSoftwareVersion', 'string', false, [3], [3], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 18;
    }

    public function getMaxSupportedVersion(): int
    {
        return 3;
    }

    public function getFlexibleVersions(): array
    {
        return [3];
    }

    public function getClientSoftwareName(): string
    {
        return $this->clientSoftwareName;
    }

    public function setClientSoftwareName(string $clientSoftwareName): self
    {
        $this->clientSoftwareName = $clientSoftwareName;

        return $this;
    }

    public function getClientSoftwareVersion(): string
    {
        return $this->clientSoftwareVersion;
    }

    public function setClientSoftwareVersion(string $clientSoftwareVersion): self
    {
        $this->clientSoftwareVersion = $clientSoftwareVersion;

        return $this;
    }
}
