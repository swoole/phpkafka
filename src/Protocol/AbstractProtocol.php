<?php

declare(strict_types=1);

namespace Longyan\Kafka\Protocol;

abstract class AbstractProtocol extends AbstractStruct
{
    abstract public function getRequestApiKey(): ?int;
}
