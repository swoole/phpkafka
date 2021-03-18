<?php

declare(strict_types=1);

namespace longlang\phpkafka\Util;

class ObjectKeyArray implements \Iterator, \ArrayAccess
{
    /**
     * @var array
     */
    protected $keys = [];

    /**
     * @var array
     */
    protected $values = [];

    public function getKeys(): array
    {
        return $this->keys;
    }

    public function getValues(): array
    {
        return $this->values;
    }

    /**
     * @param mixed $offset
     */
    public function offsetExists($offset): bool
    {
        $hash = $this->__hash($offset);
        if (null === $hash) {
            return false;
        }

        return isset($this->values[$hash]);
    }

    /**
     * @param mixed $offset
     *
     * @return mixed
     */
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

    /**
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value): void
    {
        $hash = $this->__hash($offset);
        if (null === $hash) {
            return;
        }
        $this->values[$hash] = $value;
        $this->keys[$hash] = $offset;
    }

    /**
     * @param mixed $offset
     */
    public function offsetUnset($offset): void
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

    /**
     * @return mixed
     */
    public function &current()
    {
        return $this->values[key($this->values)];
    }

    /**
     * @return mixed
     */
    public function key()
    {
        $hash = key($this->values);

        if (null === $hash) {
            return null;
        }

        return $this->keys[$hash];
    }

    /**
     * @return mixed
     */
    public function next()
    {
        return next($this->values);
    }

    /**
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->values);
    }

    public function valid(): bool
    {
        $hash = key($this->values);

        if (null === $hash) {
            return false;
        }

        return isset($this->keys[$hash]);
    }

    public function size(): int
    {
        return \count($this->values);
    }

    /**
     * @param mixed $value
     */
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
