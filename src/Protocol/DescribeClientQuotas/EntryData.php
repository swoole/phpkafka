<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\DescribeClientQuotas;

use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolField;

class EntryData extends AbstractStruct
{
    /**
     * The quota entity description.
     *
     * @var EntityData[]
     */
    protected $entity = [];

    /**
     * The quota values for the entity.
     *
     * @var ValueData[]
     */
    protected $values = [];

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('entity', EntityData::class, true, [0], [], [], [], null),
                new ProtocolField('values', ValueData::class, true, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return EntityData[]
     */
    public function getEntity(): array
    {
        return $this->entity;
    }

    /**
     * @param EntityData[] $entity
     */
    public function setEntity(array $entity): self
    {
        $this->entity = $entity;

        return $this;
    }

    /**
     * @return ValueData[]
     */
    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param ValueData[] $values
     */
    public function setValues(array $values): self
    {
        $this->values = $values;

        return $this;
    }
}
