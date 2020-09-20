<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Protocol;

use Longyan\Kafka\Protocol\ResponseHeader;
use PHPUnit\Framework\TestCase;

class ResponseHeaderTest extends TestCase
{
    private const TEST_CORRELATION_ID = 123;

    private const ENCODE_RESULT = '0000007b';

    public function testPack()
    {
        $header = new ResponseHeader();
        $header->setCorrelationId(self::TEST_CORRELATION_ID);
        $this->assertEquals(self::ENCODE_RESULT, bin2hex($header->pack()));
    }

    public function testUnpack()
    {
        $header = new ResponseHeader();
        $header->unpack(hex2bin(self::ENCODE_RESULT), $size);
        $this->assertEquals(4, $size);
        $this->assertEquals([
            'correlationId' => self::TEST_CORRELATION_ID,
        ], $header->toArray());
    }
}
