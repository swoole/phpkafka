<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Protocol;

use longlang\phpkafka\Protocol\RequestHeader\RequestHeader;
use PHPUnit\Framework\TestCase;

class RequestHeaderTest extends TestCase
{
    private const TEST_REQUEST_API_KEY = 18;

    private const TEST_REQUEST_API_VERSION = 1;

    private const TEST_CORRELATION_ID = 123;

    private const TEST_CLIENT_ID = 'test';

    private const ENCODE_RESULT_V0 = '001200010000007b';

    private const ENCODE_RESULT_V1 = '001200010000007b000474657374';

    private const ENCODE_RESULT_V2 = '001200010000007b00047465737400';

    public function testPack()
    {
        $header = new RequestHeader();
        $header->setClientId(self::TEST_CLIENT_ID);
        $header->setRequestApiKey(self::TEST_REQUEST_API_KEY);
        $header->setRequestApiVersion(self::TEST_REQUEST_API_VERSION);
        $header->setCorrelationId(self::TEST_CORRELATION_ID);
        $this->assertEquals(self::ENCODE_RESULT_V0, bin2hex($header->pack()));
        $this->assertEquals(self::ENCODE_RESULT_V1, bin2hex($header->pack(1)));
        $this->assertEquals(self::ENCODE_RESULT_V2, bin2hex($header->pack(2)));
    }

    public function testUnpack()
    {
        $header = new RequestHeader();
        $header->unpack(hex2bin(self::ENCODE_RESULT_V0), $size);
        $this->assertEquals(8, $size);
        $this->assertEquals([
            'requestApiKey'        => self::TEST_REQUEST_API_KEY,
            'requestApiVersion'    => self::TEST_REQUEST_API_VERSION,
            'correlationId'        => self::TEST_CORRELATION_ID,
            'clientId'             => null,
        ], $header->toArray());
        $header->unpack(hex2bin(self::ENCODE_RESULT_V1), $size, 1);
        $this->assertEquals(14, $size);
        $this->assertEquals([
            'requestApiKey'        => self::TEST_REQUEST_API_KEY,
            'requestApiVersion'    => self::TEST_REQUEST_API_VERSION,
            'correlationId'        => self::TEST_CORRELATION_ID,
            'clientId'             => self::TEST_CLIENT_ID,
        ], $header->toArray());
        $header->unpack(hex2bin(self::ENCODE_RESULT_V2), $size, 2);
        $this->assertEquals(15, $size);
        $this->assertEquals([
            'requestApiKey'        => self::TEST_REQUEST_API_KEY,
            'requestApiVersion'    => self::TEST_REQUEST_API_VERSION,
            'correlationId'        => self::TEST_CORRELATION_ID,
            'clientId'             => self::TEST_CLIENT_ID,
        ], $header->toArray());
    }
}
