<?php

declare(strict_types=1);

namespace longlang\phpkafka\Config;

class AbstractConfig
{
    public function __construct(array $data = [])
    {
        foreach ($data as $k => $v) {
            $methodName = 'set' . ucfirst($k);
            if (method_exists($this, $methodName)) {
                $this->$methodName($v);
            } else {
                $this->$k = $v;
            }
        }
    }
}
