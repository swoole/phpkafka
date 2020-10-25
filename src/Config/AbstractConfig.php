<?php

declare(strict_types=1);

namespace longlang\phpkafka\Config;

class AbstractConfig
{
    public function __construct(array $data = [])
    {
        foreach($data as $k => $v)
        {
            $this->$k = $v;
        }
    }
}
