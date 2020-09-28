<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\UpdateMetadata;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class UpdateMetadataBroker extends AbstractStruct
{
    /**
     * The broker id.
     *
     * @var int
     */
    protected $brokerId;

    /**
     * The broker hostname.
     *
     * @var string
     */
    protected $v0Host;

    /**
     * The broker port.
     *
     * @var int
     */
    protected $v0Port;

    /**
     * The broker endpoints.
     *
     * @var UpdateMetadataEndpoint[]
     */
    protected $endpoints = [];

    /**
     * The rack which this broker belongs to.
     *
     * @var string|null
     */
    protected $rack;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('brokerId', 'int32', false, [0, 1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('v0Host', 'string', false, [0], [6], [], [], null),
                new ProtocolField('v0Port', 'int32', false, [0], [6], [], [], null),
                new ProtocolField('endpoints', UpdateMetadataEndpoint::class, true, [1, 2, 3, 4, 5, 6], [6], [], [], null),
                new ProtocolField('rack', 'string', false, [2, 3, 4, 5, 6], [6], [0, 1, 2, 3, 4, 5, 6], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [6];
    }

    public function getBrokerId(): int
    {
        return $this->brokerId;
    }

    public function setBrokerId(int $brokerId): self
    {
        $this->brokerId = $brokerId;

        return $this;
    }

    public function getV0Host(): string
    {
        return $this->v0Host;
    }

    public function setV0Host(string $v0Host): self
    {
        $this->v0Host = $v0Host;

        return $this;
    }

    public function getV0Port(): int
    {
        return $this->v0Port;
    }

    public function setV0Port(int $v0Port): self
    {
        $this->v0Port = $v0Port;

        return $this;
    }

    /**
     * @return UpdateMetadataEndpoint[]
     */
    public function getEndpoints(): array
    {
        return $this->endpoints;
    }

    /**
     * @param UpdateMetadataEndpoint[] $endpoints
     */
    public function setEndpoints(array $endpoints): self
    {
        $this->endpoints = $endpoints;

        return $this;
    }

    public function getRack(): ?string
    {
        return $this->rack;
    }

    public function setRack(?string $rack): self
    {
        $this->rack = $rack;

        return $this;
    }
}
