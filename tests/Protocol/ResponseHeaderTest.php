<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Protocol;

use longlang\phpkafka\Protocol\ResponseHeader\ResponseHeader;
use PHPUnit\Framework\TestCase;

class ResponseHeaderTest extends TestCase
{
    private const TEST_CORRELATION_ID = 123;

    private const ENCODE_RESULT = '0000007b';

    private const ENCODE_RESULT_V1 = '0000007b00';

    public function testPack()
    {
        $header = new ResponseHeader();
        $header->setCorrelationId(self::TEST_CORRELATION_ID);
        $this->assertEquals(self::ENCODE_RESULT, bin2hex($header->pack()));
        $this->assertEquals(self::ENCODE_RESULT_V1, bin2hex($header->pack(1)));
    }

    public function testUnpack()
    {
        $header = new ResponseHeader();
        $header->unpack(hex2bin(self::ENCODE_RESULT), $size);
        $this->assertEquals(4, $size);
        $this->assertEquals([
            'correlationId' => self::TEST_CORRELATION_ID,
        ], $header->toArray());

        $header->unpack(hex2bin(self::ENCODE_RESULT_V1), $size, 1);
        $this->assertEquals(5, $size);
        $this->assertEquals([
            'correlationId' => self::TEST_CORRELATION_ID,
        ], $header->toArray());
    }
}
