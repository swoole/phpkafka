<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test\Protocol;

use Longyan\Kafka\Protocol\CreateTopics\ConfigResult;
use Longyan\Kafka\Protocol\CreateTopics\CreateTopicsRequest;
use Longyan\Kafka\Protocol\CreateTopics\CreateTopicsResponse;
use Longyan\Kafka\Protocol\CreateTopics\Topic;
use Longyan\Kafka\Protocol\CreateTopics\TopicResult;
use Longyan\Kafka\Protocol\CreateTopics\TopicsAssignment;
use Longyan\Kafka\Protocol\CreateTopics\TopicsConfig;
use PHPUnit\Framework\TestCase;

class CreateTopicsTest extends TestCase
{
    private const ENCODE_REQUEST_RESULT_V0 = '0000000100047465737400000003ffff000000010000000100000003000000010000000200000003000000010003616263000364656600002710';

    private const ENCODE_REQUEST_RESULT_V1 = '0000000100047465737400000003ffff00000001000000010000000300000001000000020000000300000001000361626300036465660000271001';

    private const ENCODE_RESPONSE_RESULT_V0 = '000000010004746573740000';

    private const ENCODE_RESPONSE_RESULT_V1 = '00000001000474657374000000076d657373616765';

    private const ENCODE_RESPONSE_RESULT_V2 = '0000271000000001000474657374000000076d657373616765';

    private const ENCODE_RESPONSE_RESULT_V5 = '000027100205746573740000086d657373616765000000030001020461626304646566017b01000000';

    public function testPackRequest()
    {
        $request = new CreateTopicsRequest();
        $request->setTopics([
            (new Topic())->setName('test')
                         ->setNumPartitions(3)
                         ->setReplicationFactor(-1)
                         ->setAssignments([
                             (new TopicsAssignment())->setPartitionIndex(1)
                                                     ->setBrokerIds([1, 2, 3]),
                         ])
                         ->setConfigs([(new TopicsConfig())
                            ->setName('abc')
                            ->setValue('def'),
                         ]),
        ]);
        $request->setTimeoutMs(10000);
        $request->setValidateOnly(true);
        $this->assertEquals(self::ENCODE_REQUEST_RESULT_V0, bin2hex($request->pack()));
        $this->assertEquals(self::ENCODE_REQUEST_RESULT_V1, bin2hex($request->pack(1)));
    }

    public function testUnpackRequest()
    {
        $request = new CreateTopicsRequest();
        $request->unpack(hex2bin(self::ENCODE_REQUEST_RESULT_V0), $size, 0);
        $this->assertEquals(58, $size);
        $this->assertEquals([
            'topics'       => [
                [
                    'name'              => 'test',
                    'numPartitions'     => 3,
                    'replicationFactor' => -1,
                    'assignments'       => [[
                        'partitionIndex' => 1,
                        'brokerIds'      => [1, 2, 3],
                    ]],
                    'configs'           => [[
                        'name'  => 'abc',
                        'value' => 'def',
                    ]],
                ],
            ],
            'timeoutMs'    => 10000,
            'validateOnly' => false,
        ], $request->toArray());

        $request->unpack(hex2bin(self::ENCODE_REQUEST_RESULT_V1), $size, 1);
        $this->assertEquals(59, $size);
        $this->assertEquals([
            'topics'       => [
                [
                    'name'              => 'test',
                    'numPartitions'     => 3,
                    'replicationFactor' => -1,
                    'assignments'       => [[
                        'partitionIndex' => 1,
                        'brokerIds'      => [1, 2, 3],
                    ]],
                    'configs'           => [[
                        'name'  => 'abc',
                        'value' => 'def',
                    ]],
                ],
            ],
            'timeoutMs'    => 10000,
            'validateOnly' => true,
        ], $request->toArray());
    }

    public function testPackResponse()
    {
        $response = new CreateTopicsResponse();
        $response->setThrottleTimeMs(10000);
        $response->setTopics([
            (new TopicResult())->setConfigs([
                (new ConfigResult())->setName('abc')->setValue('def')->setIsSensitive(true)->setReadonly(true)->setConfigSource(123),
            ])
                               ->setErrorCode(0)
                               ->setErrorMessage('message')
                               ->setName('test')
                               ->setNumPartitions(3)
                               ->setReplicationFactor(1),
        ]);

        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V0, bin2hex($response->pack()));
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V1, bin2hex($response->pack(1)));
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V2, bin2hex($response->pack(2)));
        $this->assertEquals(self::ENCODE_RESPONSE_RESULT_V5, bin2hex($response->pack(5)));
    }

    public function testUnpackResponse()
    {
        $response = new CreateTopicsResponse();
        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V0), $size);
        $this->assertEquals(12, $size);
        $this->assertEquals([
            'throttleTimeMs' => null,
            'topics'         => [[
                'name'              => 'test',
                'errorCode'         => 0,
                'errorMessage'      => null,
                'numPartitions'     => null,
                'replicationFactor' => null,
                'configs'           => [],
            ]],
        ], $response->toArray());

        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V1), $size, 1);
        $this->assertEquals(21, $size);
        $this->assertEquals([
            'throttleTimeMs' => null,
            'topics'         => [[
                'name'              => 'test',
                'errorCode'         => 0,
                'errorMessage'      => 'message',
                'numPartitions'     => null,
                'replicationFactor' => null,
                'configs'           => [],
            ]],
        ], $response->toArray());

        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V2), $size, 2);
        $this->assertEquals(25, $size);
        $this->assertEquals([
            'throttleTimeMs' => 10000,
            'topics'         => [[
                'name'              => 'test',
                'errorCode'         => 0,
                'errorMessage'      => 'message',
                'numPartitions'     => null,
                'replicationFactor' => null,
                'configs'           => [],
            ]],
        ], $response->toArray());

        $response->unpack(hex2bin(self::ENCODE_RESPONSE_RESULT_V5), $size, 5);
        $this->assertEquals(41, $size);
        $this->assertEquals([
            'throttleTimeMs' => 10000,
            'topics'         => [[
                'name'              => 'test',
                'errorCode'         => 0,
                'errorMessage'      => 'message',
                'numPartitions'     => 3,
                'replicationFactor' => 1,
                'configs'           => [[
                    'name'         => 'abc',
                    'value'        => 'def',
                    'readonly'     => true,
                    'configSource' => 123,
                    'isSensitive'  => true,
                ]],
            ]],
        ], $response->toArray());
    }
}
