<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After\FetchAndProduce;

use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;

class SnappyCompressionFetchAndProduceTest extends BaseFetchAndProduceTest
{
    public function getComporession()
    {
        return Compression::SNAPPY;
    }

    public function checkSkip()
    {
        if (!\extension_loaded('snappy')) {
            $this->markTestSkipped();
        }
    }
}
