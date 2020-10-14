<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Protocol;

use longlang\phpkafka\Protocol\DeleteTopics\DeletableTopicResult;
use longlang\phpkafka\Protocol\DeleteTopics\DeleteTopicsRequest;
use longlang\phpkafka\Protocol\DeleteTopics\DeleteTopicsResponse;
use PHPUnit\Framework\TestCase;

class DeleteTopicsTest extends TestCase
{
    private const ENCODE_REQUEST_RESULT_V0 = '0000000300036161610003626262000363636300002710';

    private const ENCODE_REQUEST_RESULT_V4 = '040461616104626262046363630000271000';

    private const ENCODE_RESPONSE_RESULT_V0 = '00000003000361616100000003626262007b00036363630000';

    private const ENCODE_RESPONSE_RESULT_V1 = '0000271000000003000361616100000003626262007b00036363630000';

    private const ENCODE_RESPONSE_RESULT_V4 = '00002710040461616100000004626262007b000463636300000000';

    public function testPackRequest()
    {
        $request = new DeleteTopicsRequest();
        $request->setTopicNames(['aaa', 'bbb', 'ccc']);
        $request->setTimeoutMs(10000);
        $this->assertEquals(self::ENCODE_REQUEST_RESULT_V0, bin2hex($request->pack()));
        $this->assertEquals(self::ENCODE_REQUEST_RESULT_V4, bin2hex($request->pack(4)));
    }

    public function testUnpackRequest()
    {
        $request = new DeleteTopicsRequest();
        $request->unpack(hex2bin(self::ENCODE_REQUEST_RESULT_V0), $size, 0);
        $this->assertEquals(23, $size);
        $this->assertEquals([
            'topicNames' => ['aaa', 'bbb', 'ccc'],
            'timeoutMs'  => 10000,
        ], $request->toArray());

        $request->unpack(hex2bin(self::ENCODE_REQUEST_RESULT_V4), $size, 4);
        $this->assertEquals(18, $size);
        $this->assertEquals([
            'topicNames' => ['aaa', 'bbb', 'ccc'],
            'timeoutMs'  => 10000,
        ], $request->toArray());
    }

    public function testPackResponse()
    {
        $response = new DeleteTopicsResponse();
        $response->setThrottleTimeMs(10000);
        $response->setResponses([
            (new DeletableTopicResult())->setName('aaa')->setErrorCode(0),
            (new DeletableTopicResult())->setName('bbb')->setErrorCode(123),
            (new DeletableTopicResult())->setName('ccc')->setErrorCode(0),
        ]);

        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V0, bin2hex($response->pack()));
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V1, bin2hex($response->pack(1)));
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V4, bin2hex($response->pack(4)));
    }

    public function testUnpackResponse()
    {
        $response = new DeleteTopicsResponse();
        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V0), $size);
        $this->assertEquals(25, $size);
        $this->assertEquals([
            'throttleTimeMs' => null,
            'responses'      => [
                ['name' => 'aaa', 'errorCode' => 0],
                ['name' => 'bbb', 'errorCode' => 123],
                ['name' => 'ccc', 'errorCode' => 0],
            ],
        ], $response->toArray());

        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V1), $size, 1);
        $this->assertEquals(29, $size);
        $this->assertEquals([
            'throttleTimeMs' => 10000,
            'responses'      => [
                ['name' => 'aaa', 'errorCode' => 0],
                ['name' => 'bbb', 'errorCode' => 123],
                ['name' => 'ccc', 'errorCode' => 0],
            ],
        ], $response->toArray());

        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V4), $size, 4);
        $this->assertEquals(27, $size);
        $this->assertEquals([
            'throttleTimeMs' => 10000,
            'responses'      => [
                ['name' => 'aaa', 'errorCode' => 0],
                ['name' => 'bbb', 'errorCode' => 123],
                ['name' => 'ccc', 'errorCode' => 0],
            ],
        ], $response->toArray());
    }
}
