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
        if (!\extension_loaded('zstd') || version_compare(getenv('KAFKA_VERSION') ?: '0', '2.1', '<')) {
            $this->markTestSkipped();
        }
    }
}
