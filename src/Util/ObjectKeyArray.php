<?php

declare(strict_types=1);

namespace longlang\phpkafka\Util;

class ObjectKeyArray implements \Iterator, \ArrayAccess
{
    protected $keys = [];

    protected $values = [];

    public function getKeys(): array
    {
        return $this->keys;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    public function offsetExists($offset)
    {
        $hash = $this->__hash($offset);
        if (null === $hash) {
            return false;
        }

        return isset($this->values[$hash]);
    }

    public function &offsetGet($offset)
    {
        $hash = $this->__hash($offset);
        if (null === $hash) {
            $value = null;

            return $value;
        }

        if (!isset($this->values[$hash])) {
            $value = null;

            return $value;
        }

        return $this->values[$hash];
    }

    public function offsetSet($offset, $value)
    {
        $hash = $this->__hash($offset);
        if (null === $hash) {
            return;
        }
        $this->values[$hash] = $value;
        $this->keys[$hash] = $offset;
    }

    public function offsetUnset($offset)
    {
        $hash = $this->__hash($offset);
        if (null === $hash) {
            return;
        }

        if (!isset($this->values[$hash])) {
            return;
        }

        unset($this->values[$hash], $this->keys[$hash]);
    }

    public function &current()
    {
        return $this->values[key($this->values)];
    }

    public function key()
    {
        $hash = key($this->values);

        if (null === $hash) {
            return null;
        }

        return $this->keys[$hash];
    }

    public function next()
    {
        return next($this->values);
    }

    public function rewind()
    {
        return reset($this->values);
    }

    public function valid()
    {
        $hash = key($this->values);

        if (null === $hash) {
            return false;
        }

        return isset($this->keys[$hash]);
    }

    public function size()
    {
        return \count($this->values);
    }

    private function __hash($value): ?string
    {
        if (!\is_object($value)) {
            return null;
        }
        if (method_exists($value, '__toString')) {
            return (string) $value;
        } else {
            return spl_object_hash($value);
        }
    }
}
