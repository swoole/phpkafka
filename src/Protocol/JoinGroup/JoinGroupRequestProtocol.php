<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\JoinGroup;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class JoinGroupRequestProtocol extends AbstractStruct
{
    /**
     * The protocol name.
     *
     * @var string
     */
    protected $name = '';

    /**
     * The protocol metadata.
     *
     * @var string
     */
    protected $metadata = '';

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'string', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
                new ProtocolField('metadata', 'bytes', false, [0, 1, 2, 3, 4, 5, 6, 7], [6, 7], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [6, 7];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getMetadata(): string
    {
        return $this->metadata;
    }

    public function setMetadata(string $metadata): self
    {
        $this->metadata = $metadata;

        return $this;
    }
}
