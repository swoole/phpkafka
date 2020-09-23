<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol\DeleteTopics;

use Longyan\Kafka\Protocol\AbstractStruct;
use Longyan\Kafka\Protocol\ProtocolField;

class DeleteResponse extends AbstractStruct
{
    /**
     * The topic name.
     *
     * @var string
     */
    protected $name;

    /**
     * The deletion error, or 0 if the deletion succeeded.
     *
     * @var int
     */
    protected $errorCode;

    public function __construct()
    {
        if (!isset(self::$maps[self::class])) {
            self::$maps[self::class] = [
                new ProtocolField('name', 'CompactString', null, 4),
                new ProtocolField('name', 'String16', null, 0),
                new ProtocolField('errorCode', 'Int16', null, 0),
            ];
            self::$taggedFieldses[self::class] = [];
        }
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

    public function getErrorCode(): int
    {
        return $this->errorCode;
    }

    public function setErrorCode(int $errorCode): self
    {
        $this->errorCode = $errorCode;

        return $this;
    }
}
