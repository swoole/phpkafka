<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

abstract class AbstractProtocol extends AbstractStruct
{
    abstract public function getRequestApiKey(): ?int;
}
