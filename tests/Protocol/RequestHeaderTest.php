<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Protocol;

use Longyan\Kafka\Protocol\RequestHeader;
use PHPUnit\Framework\TestCase;

class RequestHeaderTest extends TestCase
{
    private const TEST_REQUEST_API_KEY = 18;

    private const TEST_REQUEST_API_VERSION = 1;

    private const TEST_CORRELATION_ID = 123;

    private const TEST_CLIENT_ID = 'test';

    private const ENCODE_RESULT_V0 = '001200010000007b';

    private const ENCODE_RESULT_V1 = '001200010000007b000474657374';

    public function testPack()
    {
        $header = new RequestHeader(self::TEST_REQUEST_API_KEY, self::TEST_REQUEST_API_VERSION, self::TEST_CORRELATION_ID, 'test');
        $this->assertEquals(self::ENCODE_RESULT_V0, bin2hex($header->pack()));
        $this->assertEquals(self::ENCODE_RESULT_V1, bin2hex($header->pack(1)));
    }

    public function testUnpack()
    {
        $header = new RequestHeader();
        $header->unpack(hex2bin(self::ENCODE_RESULT_V0), $size);
        $this->assertEquals(8, $size);
        $header->unpack(hex2bin(self::ENCODE_RESULT_V1), $size, 1);
        $this->assertEquals(14, $size);
        $this->assertEquals([
            'requestApiKey'     => self::TEST_REQUEST_API_KEY,
            'requestApiVersion' => self::TEST_REQUEST_API_VERSION,
            'correlationId'     => self::TEST_CORRELATION_ID,
            'clientId'          => self::TEST_CLIENT_ID,
        ], $header->toArray());
    }
}
