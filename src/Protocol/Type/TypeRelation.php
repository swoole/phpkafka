<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol\Type;

class TypeRelation
{
    public const INDEX_PHP_TYPE = 0;

    public const INDEX_UNCOMPACT_TYPE = 1;

    public const INDEX_COMPACT_TYPE = 2;

    public const INDEX_NULLABLE_TYPE = 3;

    public const INDEX_COMPACT_NULLABLE_TYPE = 4;

    public const TYPE_RELATION = [
        // kafka type => phptype, uncompactType, compactType, nullableType, compactNullableType
        '[]'          => ['array', 'ArrayInt32', 'CompactArray', 'ArrayInt32', 'CompactArray'],
        'bool'        => ['bool', 'Boolean', 'Boolean', 'Boolean', 'Boolean'],
        'float64'     => ['float', 'Float64', 'Float64', 'Float64', 'Float64'],
        'int8'        => ['int', 'Int8', 'Int8', 'Int8', 'Int8'],
        'int16'       => ['int', 'Int16', 'Int16', 'Int16', 'Int16'],
        'int32'       => ['int', 'Int32', 'Int32', 'Int32', 'Int32'],
        'int64'       => ['int', 'Int64', 'Int64', 'Int64', 'Int64'],
        'string'      => ['string', 'String16', 'CompactString', 'NullableString', 'CompactNullableString'],
        'bytes'       => ['string', 'String16', 'CompactString', 'NullableString', 'CompactNullableString'],
        'varint'      => ['int', 'varint', 'varint', 'varint', 'varint'],
        'RecordBatch' => ['\longlang\phpkafka\Protocol\RecordBatch\RecordBatch', '\longlang\phpkafka\Protocol\RecordBatch\RecordBatch', '\longlang\phpkafka\Protocol\RecordBatch\RecordBatch', '\longlang\phpkafka\Protocol\RecordBatch\RecordBatch'],
    ];

    private function __construct()
    {
    }
}
