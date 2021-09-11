<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;
use PHPUnit\Framework\TestCase;

class ConsumerTest extends TestCase
{
    public function testConsumeWithRangeAssignor(): void
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
        TestUtil::addConfigInfo($config);
        $config->setTopic('test');
        $config->setGroupId('testGroup');
        $config->setClientId('testConsumeWithRangeAssignor');
        $config->setGroupInstanceId('testConsumeWithRangeAssignor');
        $config->setPartitionAssignmentStrategy(\longlang\phpkafka\Consumer\Assignor\RangeAssignor::class);
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function (ConsumeMessage $message) {
            $consumer = $message->getConsumer();
            $this->assertNotEmpty($message->getValue());
            $consumer->stop();
        });
        $consumer->start();
        $consumer->close();
    }

    public function testConsumeWithRoundRobinAssignor(): void
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
        TestUtil::addConfigInfo($config);
        $config->setTopic('test');
        $config->setGroupId('testGroup');
        $config->setClientId('testConsumeWithRoundRobinAssignor');
        $config->setGroupInstanceId('testConsumeWithRoundRobinAssignor');
        $config->setPartitionAssignmentStrategy(\longlang\phpkafka\Consumer\Assignor\RoundRobinAssignor::class);
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function (ConsumeMessage $message) {
            $consumer = $message->getConsumer();
            $this->assertNotEmpty($message->getValue());
            $consumer->stop();
        });
        $consumer->start();
        $consumer->close();
    }

    public function testConsumeWithStickyAssignor(): void
    {
        $config = new ConsumerConfig();
        $config->setBroker([TestUtil::getHost() . ':' . TestUtil::getPort()]);
        TestUtil::addConfigInfo($config);
        $config->setTopic('test');
        $config->setGroupId('testGroup');
        $config->setClientId('testConsumeWithStickyAssignor');
        $config->setGroupInstanceId('testConsumeWithStickyAssignor');
        $config->setPartitionAssignmentStrategy(\longlang\phpkafka\Consumer\Assignor\StickyAssignor::class);
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function (ConsumeMessage $message) {
            $consumer = $message->getConsumer();
            $this->assertNotEmpty($message->getValue());
            $consumer->stop();
        });
        $consumer->start();
        $consumer->close();
    }

    public function testConsumeWithHeader(): void
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
        TestUtil::addConfigInfo($config);
        $config->setTopic('test-header');
        $config->setGroupId('testGroup');
        $config->setClientId('testConsumeWithHeader');
        $config->setGroupInstanceId('testConsumeWithHeader');
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function (ConsumeMessage $message) {
            $consumer = $message->getConsumer();
            $this->assertNotEmpty($message->getValue());
            $headers = $message->getHeaders();
            $this->assertCount(2, $headers);
            $this->assertEquals('key1', $headers[0]->getHeaderKey());
            $this->assertEquals('value1', $headers[0]->getValue());
            $this->assertEquals('key2', $headers[1]->getHeaderKey());
            $this->assertEquals('value2', $headers[1]->getValue());
            $consumer->stop();
        });
        $consumer->start();
        $consumer->close();
    }
}
