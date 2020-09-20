<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Protocol;

use Longyan\Kafka\Protocol\ApiVersions\ApiKeys;
use Longyan\Kafka\Protocol\ApiVersions\ApiVersionsRequest;
use Longyan\Kafka\Protocol\ApiVersions\ApiVersionsResponse;
use PHPUnit\Framework\TestCase;

class ApiVersionsTest extends TestCase
{
    private const ENCODE_REQUEST_RESULT = '116c6f6e6779616e2d6b61666b612d70687005312e302e30';

    private const ENCODE_RESPONSE_RESULT_V0 = '007b00000001001200000064';

    private const ENCODE_RESPONSE_RESULT_V1 = '007b0000000100120000006400002710';

    public function testPackRequest()
    {
        // no fields
        $request = new ApiVersionsRequest();
        $this->assertEquals('', $request->pack());

        $this->assertEquals(self::ENCODE_REQUEST_RESULT, bin2hex($request->pack(3)));
    }

    public function testUnpackRequest()
    {
        $request = new ApiVersionsRequest();
        $request->unpack(hex2bin(self::ENCODE_REQUEST_RESULT), $size, 0);
        $this->assertEquals(0, $size);
        $request->unpack(hex2bin(self::ENCODE_REQUEST_RESULT), $size, 3);
        $this->assertEquals(24, $size);
        $this->assertEquals([
            'clientSoftwareName'    => 'longyan-kafka-php',
            'clientSoftwareVersion' => '1.0.0',
        ], $request->toArray());
    }

    public function testPackResponse()
    {
        $response = new ApiVersionsResponse();
        $response->setApiKeys([
            (new ApiKeys())->setApiKey(18)->setMaxVersion(100)->setMinVersion(0),
        ]);
        $response->setErrorCode(123);
        $response->setThrottleTimeMs(10000);
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V0, bin2hex($response->pack()));
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V1, bin2hex($response->pack(1)));
    }

    public function testUnpackResponse()
    {
        $response = new ApiVersionsResponse();
        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V0), $size);
        $this->assertEquals(12, $size);
        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V1), $size, 1);
        $this->assertEquals(16, $size);
        $this->assertEquals([
            'errorCode'             => 123,
            'apiKeys'               => [[
                'apiKey'            => 18,
                'maxVersion'        => 100,
                'minVersion'        => 0,
            ]],
            'throttleTimeMs'    => 10000,
        ], $response->toArray());
    }
}
