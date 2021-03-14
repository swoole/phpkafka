<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After\FetchAndProduce;

use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;

class LZ4CompressionFetchAndProduceTest extends BaseFetchAndProduceTest
{
    public function getComporession(): int
    {
        return Compression::LZ4;
    }

    public function checkSkip(): void
    {
        if (!\extension_loaded('lz4')) {
            $this->markTestSkipped();
        }
    }
}
