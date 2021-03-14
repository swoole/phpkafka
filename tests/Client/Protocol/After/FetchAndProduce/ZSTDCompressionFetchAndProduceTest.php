<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After\FetchAndProduce;

use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;

class ZSTDCompressionFetchAndProduceTest extends BaseFetchAndProduceTest
{
    public function getComporession()
    {
        return Compression::ZSTD;
    }

    public function checkSkip()
    {
        $kafkaVersion = getenv('KAFKA_VERSION') ?: '0';
        $list = explode('-', $kafkaVersion);
        if (isset($list[1])) {
            $kafkaVersion = $list[1];
        } else {
            $kafkaVersion = $list[0];
        }
        if (!\extension_loaded('zstd') || version_compare($kafkaVersion, '2.1', '<')) {
            $this->markTestSkipped();
        }
    }
}
