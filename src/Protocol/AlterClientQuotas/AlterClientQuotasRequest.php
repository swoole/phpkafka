<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\AlterClientQuotas;

use longlang\phpkafka\Protocol\AbstractRequest;
use longlang\phpkafka\Protocol\ProtocolField;

class AlterClientQuotasRequest extends AbstractRequest
{
    /**
     * The quota configuration entries to alter.
     *
     * @var EntryData[]
     */
    protected $entries = [];

    /**
     * Whether the alteration should be validated, but not performed.
     *
     * @var bool
     */
    protected $validateOnly = false;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('entries', EntryData::class, true, [0], [], [], [], null),
                new ProtocolField('validateOnly', 'bool', false, [0], [], [], [], null),
            ];
            self::$taggedFieldses[self::class] = [
            ];
        }
    }

    public function getRequestApiKey(): ?int
    {
        return 49;
    }

    public function getMaxSupportedVersion(): int
    {
        return 0;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    /**
     * @return EntryData[]
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @param EntryData[] $entries
     */
    public function setEntries(array $entries): self
    {
        $this->entries = $entries;

        return $this;
    }

    public function getValidateOnly(): bool
    {
        return $this->validateOnly;
    }

    public function setValidateOnly(bool $validateOnly): self
    {
        $this->validateOnly = $validateOnly;

        return $this;
    }
}
