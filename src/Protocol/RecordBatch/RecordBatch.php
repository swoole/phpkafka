<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\RecordBatch;

use longlang\phpkafka\Exception\CRC32Exception;
use longlang\phpkafka\Exception\UnsupportedCompressionException;
use longlang\phpkafka\Protocol\AbstractStruct;
use longlang\phpkafka\Protocol\ProtocolUtil;
use longlang\phpkafka\Protocol\RecordBatch\Enum\Compression;
use longlang\phpkafka\Protocol\Type\ArrayInt32;
use longlang\phpkafka\Protocol\Type\Int16;
use longlang\phpkafka\Protocol\Type\Int32;
use longlang\phpkafka\Protocol\Type\Int64;
use longlang\phpkafka\Protocol\Type\Int8;
use longlang\phpkafka\Protocol\Type\String32;
use longlang\phpkafka\Util\LZ4;

class RecordBatch extends AbstractStruct
{
    /**
     * @var int
     */
    protected $baseOffset = 0;

    /**
     * @var int
     */
    protected $batchLength = 0;

    /**
     * @var int
     */
    protected $partitionLeaderEpoch = 0;

    /**
     * @var int
     */
    protected $magic = 2;

    /**
     * @var int
     */
    protected $crc = 0;

    /**
     * @var Attributes
     */
    protected $attributes;

    /**
     * @var int
     */
    protected $lastOffsetDelta = 0;

    /**
     * @var int
     */
    protected $firstTimestamp = 0;

    /**
     * @var int
     */
    protected $maxTimestamp = 0;

    /**
     * @var int
     */
    protected $producerId = 0;

    /**
     * @var int
     */
    protected $producerEpoch = 0;

    /**
     * @var int
     */
    protected $baseSequence = 0;

    /**
     * @var Record[]
     */
    protected $records = [];

    public function __construct()
    {
        $this->attributes = new Attributes();
    }

    public function pack(int $apiVersion = 0): string
    {
        $data = '';
        $data .= Int16::pack($this->attributes->getValue());
        $data .= Int32::pack($this->lastOffsetDelta);
        $data .= Int64::pack($this->firstTimestamp);
        $data .= Int64::pack($this->maxTimestamp);
        $data .= Int64::pack($this->producerId);
        $data .= Int16::pack($this->producerEpoch);
        $data .= Int32::pack($this->baseSequence);
        $compression = $this->attributes->getCompression();
        if (Compression::NONE === $compression) {
            $data .= ArrayInt32::pack($this->records, null, $apiVersion);
        } else {
            $unCompressionContent = '';
            foreach ($this->records as $record) {
                $unCompressionContent .= $record->pack($apiVersion);
            }
            $data .= Int32::pack(\count($this->records));
            switch ($compression) {
                case Compression::GZIP:
                    $data .= gzencode($unCompressionContent);
                    break;
                case Compression::SNAPPY:
                    if (\function_exists('snappy_compress')) {
                        $data .= snappy_compress($unCompressionContent);
                    } else {
                        throw new \RuntimeException('Please install and enable snappy extension first: https://github.com/kjdev/php-ext-snappy');
                    }
                    break;
                case Compression::LZ4:
                    $data .= LZ4::compress($unCompressionContent);
                    break;
                case Compression::ZSTD:
                    if (\function_exists('zstd_compress')) {
                        $data .= zstd_compress($unCompressionContent);
                    } else {
                        throw new \RuntimeException('Please install and enable zstd extension first: https://github.com/kjdev/php-ext-zstd');
                    }
                    break;
                default:
                    throw new UnsupportedCompressionException(sprintf('Unsupport compression %s', $compression));
            }
        }

        $result = '';
        $result .= Int64::pack($this->baseOffset);
        $result .= Int32::pack($this->batchLength = 4 + 1 + 4 + \strlen($data));
        $result .= Int32::pack($this->partitionLeaderEpoch);
        $result .= Int8::pack($this->magic);
        $result .= Int32::pack(ProtocolUtil::int32(hexdec(ProtocolUtil::crc32c($data))));
        $result .= $data;

        return String32::pack($result);
    }

    public function unpack(string $data, ?int &$size = null, int $apiVersion = 0): void
    {
        $length = Int32::unpack($data, $tmpSize);
        $size = $tmpSize;
        if ($length <= 0) {
            return;
        }
        $size += $length;
        $data = substr($data, $tmpSize, $length);

        $this->baseOffset = Int64::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->batchLength = Int32::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->partitionLeaderEpoch = Int32::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->magic = Int8::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->crc = Int32::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        if (ProtocolUtil::int32(hexdec(ProtocolUtil::crc32c($data))) !== $this->crc) {
            throw new CRC32Exception('crc32 verification failed');
        }

        $this->attributes->setValue(Int16::unpack($data, $tmpSize));
        $data = substr($data, $tmpSize);

        $this->lastOffsetDelta = Int32::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->firstTimestamp = Int64::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->maxTimestamp = Int64::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->producerId = Int64::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->producerEpoch = Int16::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $this->baseSequence = Int32::unpack($data, $tmpSize);
        $data = substr($data, $tmpSize);

        $lengthBin = substr($data, 0, 4);
        $data = substr($data, 4);

        $compression = $this->attributes->getCompression();
        if (Compression::NONE !== $compression) {
            switch ($compression) {
                case Compression::GZIP:
                    $data = gzdecode($data);
                    break;
                case Compression::SNAPPY:
                    if (\function_exists('snappy_uncompress')) {
                        $data = snappy_uncompress($data);
                    } else {
                        throw new \RuntimeException('Please install and enable snappy extension first: https://github.com/kjdev/php-ext-snappy');
                    }
                    break;
                case Compression::LZ4:
                    if (\function_exists('lz4_uncompress')) {
                        $data = LZ4::uncompress($data);
                    } else {
                        throw new \RuntimeException('Please install and enable lz4 extension first: https://github.com/kjdev/php-ext-lz4');
                    }
                    break;
                case Compression::ZSTD:
                    if (\function_exists('zstd_uncompress')) {
                        $data = zstd_uncompress($data);
                    } else {
                        throw new \RuntimeException('Please install and enable zstd extension first: https://github.com/kjdev/php-ext-zstd');
                    }
                    break;
                default:
                    throw new UnsupportedCompressionException(sprintf('Unsupport compression %s', $compression));
            }
        }
        $this->records = ArrayInt32::unpack($lengthBin . $data, $tmpSize, Record::class);
    }

    public function toArray(): array
    {
        $array = [
            'baseOffset'           => $this->baseOffset,
            'batchLength'          => $this->batchLength,
            'partitionLeaderEpoch' => $this->partitionLeaderEpoch,
            'magic'                => $this->magic,
            'crc'                  => $this->crc,
            'attributes'           => $this->attributes->getValue(),
            'lastOffsetDelta'      => $this->lastOffsetDelta,
            'firstTimestamp'       => $this->firstTimestamp,
            'maxTimestamp'         => $this->maxTimestamp,
            'producerId'           => $this->producerId,
            'producerEpoch'        => $this->producerEpoch,
            'baseSequence'         => $this->baseSequence,
        ];
        $records = [];
        foreach ($this->records as $record) {
            $records[] = $record->toArray();
        }
        $array['records'] = $records;

        return $array;
    }

    public function getFlexibleVersions(): array
    {
        return [];
    }

    public function getBaseOffset(): int
    {
        return $this->baseOffset;
    }

    public function setBaseOffset(int $baseOffset): self
    {
        $this->baseOffset = $baseOffset;

        return $this;
    }

    public function getBatchLength(): int
    {
        return $this->batchLength;
    }

    public function getPartitionLeaderEpoch(): int
    {
        return $this->partitionLeaderEpoch;
    }

    public function setPartitionLeaderEpoch(int $partitionLeaderEpoch): self
    {
        $this->partitionLeaderEpoch = $partitionLeaderEpoch;

        return $this;
    }

    public function getMagic(): int
    {
        return $this->magic;
    }

    public function setMagic(int $magic): self
    {
        $this->magic = $magic;

        return $this;
    }

    public function getCrc(): int
    {
        return $this->crc;
    }

    public function getAttributes(): Attributes
    {
        return $this->attributes;
    }

    public function setAttributes(Attributes $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function getLastOffsetDelta(): int
    {
        return $this->lastOffsetDelta;
    }

    public function setLastOffsetDelta(int $lastOffsetDelta): self
    {
        $this->lastOffsetDelta = $lastOffsetDelta;

        return $this;
    }

    public function getFirstTimestamp(): int
    {
        return $this->firstTimestamp;
    }

    public function setFirstTimestamp(int $firstTimestamp): self
    {
        $this->firstTimestamp = $firstTimestamp;

        return $this;
    }

    public function getMaxTimestamp(): int
    {
        return $this->maxTimestamp;
    }

    public function setMaxTimestamp(int $maxTimestamp): self
    {
        $this->maxTimestamp = $maxTimestamp;

        return $this;
    }

    public function getProducerId(): int
    {
        return $this->producerId;
    }

    public function setProducerId(int $producerId): self
    {
        $this->producerId = $producerId;

        return $this;
    }

    public function getProducerEpoch(): int
    {
        return $this->producerEpoch;
    }

    public function setProducerEpoch(int $producerEpoch): self
    {
        $this->producerEpoch = $producerEpoch;

        return $this;
    }

    public function getBaseSequence(): int
    {
        return $this->baseSequence;
    }

    public function setBaseSequence(int $baseSequence): self
    {
        $this->baseSequence = $baseSequence;

        return $this;
    }

    /**
     * @return Record[]
     */
    public function getRecords(): array
    {
        return $this->records;
    }

    /**
     * @param Record[] $records
     */
    public function setRecords(array $records): self
    {
        $this->records = $records;

        return $this;
    }
}
