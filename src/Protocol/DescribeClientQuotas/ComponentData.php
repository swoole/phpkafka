<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DescribeClientQuotas;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class ComponentData extends AbstractStruct
{
    /**
     * The entity type that the filter component applies to.
     *
     * @var string
     */
    protected $entityType;

    /**
     * How to match the entity {0 = exact name, 1 = default name, 2 = any specified name}.
     *
     * @var int
     */
    protected $matchType;

    /**
     * The string to match against, or null if unused for the match type.
     *
     * @var string|null
     */
    protected $match;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('entityType', 'string', false, [0], [], [], [], null),
                new ProtocolField('matchType', 'int8', false, [0], [], [], [], null),
                new ProtocolField('match', 'string', false, [0], [], [0], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getEntityType(): string
    {
        return $this->entityType;
    }

    public function setEntityType(string $entityType): self
    {
        $this->entityType = $entityType;

        return $this;
    }

    public function getMatchType(): int
    {
        return $this->matchType;
    }

    public function setMatchType(int $matchType): self
    {
        $this->matchType = $matchType;

        return $this;
    }

    public function getMatch(): ?string
    {
        return $this->match;
    }

    public function setMatch(?string $match): self
    {
        $this->match = $match;

        return $this;
    }
}
