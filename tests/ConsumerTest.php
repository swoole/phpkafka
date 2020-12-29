<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test;

use longlang\phpkafka\Consumer\ConsumeMessage;
use longlang\phpkafka\Consumer\Consumer;
use longlang\phpkafka\Consumer\ConsumerConfig;
use PHPUnit\Framework\TestCase;

class ConsumerTest extends TestCase
{
    public function testConsumeWithRangeAssignor()
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
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

    public function testConsumeWithRoundRobinAssignor()
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
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

    public function testConsumeWithStickyAssignor()
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
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
}
