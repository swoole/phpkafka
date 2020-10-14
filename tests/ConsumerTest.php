<?php

declare(strict_types=1);

namespace Longyan\Kafka\Test;

use Longyan\Kafka\Consumer\ConsumeMessage;
use Longyan\Kafka\Consumer\Consumer;
use Longyan\Kafka\Consumer\ConsumerConfig;
use PHPUnit\Framework\TestCase;

class ConsumerTest extends TestCase
{
    public function testConsume()
    {
        $config = new ConsumerConfig();
        $config->setBroker(TestUtil::getHost() . ':' . TestUtil::getPort());
        $config->setTopic('test');
        $config->setInterval(0.1);
        $consumer = new Consumer($config, function (ConsumeMessage $message) {
            $consumer = $message->getConsumer();
            $this->assertNotEmpty($message->getValue());
            $consumer->stop();
            $consumer->close();
        });
        $consumer->start();
    }
}
