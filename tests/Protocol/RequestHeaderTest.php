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

    private const INITIAL_CLIENT_ID = 'testInitialClientId';

    private const INITIAL_PRINCIPAL_NAME = 'testInitialPrincipalName';

    private const ENCODE_RESULT_V0 = '001200010000007b';

    private const ENCODE_RESULT_V1 = '001200010000007b000474657374';

    private const ENCODE_RESULT_V2 = '001200010000007b0004746573740200191974657374496e697469616c5072696e636970616c4e616d6501141474657374496e697469616c436c69656e744964';

    public function testPack()
    {
        $header = new RequestHeader(self::TEST_REQUEST_API_KEY, self::TEST_REQUEST_API_VERSION, self::TEST_CORRELATION_ID, 'test');
        $header->setInitialClientId(self::INITIAL_CLIENT_ID);
        $header->setInitialPrincipalName(self::INITIAL_PRINCIPAL_NAME);
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
            'initialPrincipalName' => null,
            'initialClientId'      => null,
        ], $header->toArray());
        $header->unpack(hex2bin(self::ENCODE_RESULT_V1), $size, 1);
        $this->assertEquals(14, $size);
        $this->assertEquals([
            'requestApiKey'        => self::TEST_REQUEST_API_KEY,
            'requestApiVersion'    => self::TEST_REQUEST_API_VERSION,
            'correlationId'        => self::TEST_CORRELATION_ID,
            'clientId'             => self::TEST_CLIENT_ID,
            'initialPrincipalName' => null,
            'initialClientId'      => null,
        ], $header->toArray());
        $header->unpack(hex2bin(self::ENCODE_RESULT_V2), $size, 2);
        $this->assertEquals(64, $size);
        $this->assertEquals([
            'requestApiKey'        => self::TEST_REQUEST_API_KEY,
            'requestApiVersion'    => self::TEST_REQUEST_API_VERSION,
            'correlationId'        => self::TEST_CORRELATION_ID,
            'clientId'             => self::TEST_CLIENT_ID,
            'initialClientId'      => self::INITIAL_CLIENT_ID,
            'initialPrincipalName' => self::INITIAL_PRINCIPAL_NAME,
        ], $header->toArray());
    }
}
