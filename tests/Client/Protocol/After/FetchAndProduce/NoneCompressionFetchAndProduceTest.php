<?php

declare(strict_types=1);

namespace longlang\phpkafka\Test\Client\Protocol\After\FetchAndProduce;

use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;

class NoneCompressionFetchAndProduceTest extends BaseFetchAndProduceTest
{
    public function getComporession()
    {
        return Compression::NONE;
    }

    public function checkSkip()
    {
    }
}
