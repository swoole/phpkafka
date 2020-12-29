<?php

declare(strict_types=1);

namespace longlang\phpkafka\Consumer\Struct;

class ConsumerPair
{
    /**
     * @var string
     */
    private $srcMemberId;

    /**
     * @var string
     */
    private $dstMemberId;

    public function __construct(string $srcMemberId, string $dstMemberId)
    {
        $this->srcMemberId = $srcMemberId;
        $this->dstMemberId = $dstMemberId;
    }

    public function __toString()
    {
        return $this->srcMemberId . '->' . $this->dstMemberId;
    }

    public function getSrcMemberId(): string
    {
        return $this->srcMemberId;
    }

    public function getDstMemberId(): string
    {
        return $this->dstMemberId;
    }

    public function in(array $pairs): bool
    {
        $thisString = (string) $this;
        foreach ($pairs as $pair) {
            if ((string) $pair === $thisString) {
                return true;
            }
        }

        return false;
    }
}
