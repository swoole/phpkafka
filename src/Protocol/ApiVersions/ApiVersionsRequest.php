<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\ApiVersions;

use Longyan\Kafka\Protocol\AbstractRequest;
use Longyan\Kafka\Protocol\ApiKeys;
use Longyan\Kafka\Protocol\ProtocolField;

class ApiVersionsRequest extends AbstractRequest
{
    /**
     * The name of the client.
     *
     * @var string|null
     */
    protected $clientSoftwareName = 'longyan-kafka-php';

    /**
     * The version of the client.
     *
     * @var string|null
     */
    protected $clientSoftwareVersion = '1.0.0';

    public function __construct()
    {
        $this->map = [
            new ProtocolField('clientSoftwareName', 'CompactString', null, 3),
            new ProtocolField('clientSoftwareVersion', 'CompactString', null, 3),
        ];
    }

    public function getRequestApiKey(): ?int
    {
        return ApiKeys::PROTOCOL_API_VERSIONS;
    }

    public function getMaxSupportedVersion(): int
    {
        return 3;
    }

    public function getClientSoftwareName(): ?string
    {
        return $this->clientSoftwareName;
    }

    public function setClientSoftwareName(?string $clientSoftwareName): self
    {
        $this->clientSoftwareName = $clientSoftwareName;

        return $this;
    }

    public function getClientSoftwareVersion(): ?string
    {
        return $this->clientSoftwareVersion;
    }

    public function setClientSoftwareVersion(?string $clientSoftwareVersion): self
    {
        $this->clientSoftwareVersion = $clientSoftwareVersion;

        return $this;
    }
}
