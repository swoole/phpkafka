<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After\FetchAndProduce;

use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;

class GzipCompressionFetchAndProduceTest extends BaseFetchAndProduceTest
{
    public function getComporession()
    {
        return Compression::GZIP;
    }

    public function checkSkip()
    {
    }
}
