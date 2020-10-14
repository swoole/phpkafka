<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

class ApiVersion
{
    private function __construct()
    {
    }

    public const V0_8_0 = 1;

    public const V0_8_1 = 2;

    public const V0_8_2 = 3;

    public const V0_9_0 = 4;

    // 0.10.0.0 is introduced for KIP-31/32 which changes the message format.
    public const V0_10_0_0 = 5;

    // 0.10.0.1 is introduced for KIP-36(rack awareness) and KIP-43(SASL handshake).
    public const V0_10_0_1 = 6;

    // introduced for JoinGroup protocol change in KIP-62
    public const V0_10_1_0 = 7;

    // 0.10.1.1 is introduced for KIP-74(fetch response size limit).
    public const V0_10_1_1 = 8;

    // introduced ListOffsetRequest v1 in KIP-79
    public const V0_10_1_2 = 9;

    // introduced UpdateMetadataRequest v3 in KIP-103
    public const V0_10_2_0 = 10;

    // KIP-98 (idempotent and transactional producer support)
    public const V0_11_0_0 = 11;

    // introduced DeleteRecordsRequest v0 and FetchRequest v4 in KIP-107
    public const V0_11_0_1 = 12;

    // Introduced leader epoch fetches to the replica fetcher via KIP-101
    public const V0_11_0_2 = 13;

    // Introduced LeaderAndIsrRequest V1, UpdateMetadataRequest V4 and FetchRequest V6 via KIP-112
    public const V1_0_0 = 14;

    // Introduced DeleteGroupsRequest V0 via KIP-229, plus KIP-227 incremental fetch requests,
    // and KafkaStorageException for fetch requests.
    public const V1_1_0 = 15;

    // Introduced OffsetsForLeaderEpochRequest V1 via KIP-279 (Fix log divergence between leader and follower after fast leader fail over)
    public const V2_0_0 = 16;

    // Several request versions were bumped due to KIP-219 (Improve quota communication)
    public const V2_0_1 = 17;

    // Introduced new schemas for group offset (v2) and group metadata (v2) (KIP-211)
    public const V2_1_0 = 18;

    // New Fetch, OffsetsForLeaderEpoch, and ListOffsets schemas (KIP-320)
    public const V2_1_1 = 19;

    // Support ZStandard Compression Codec (KIP-110)
    public const V2_1_2 = 20;

    // Introduced broker generation (KIP-380), and
    // LeaderAdnIsrRequest V2, UpdateMetadataRequest V5, StopReplicaRequest V1
    public const V2_2_0 = 21;

    // New error code for ListOffsets when a new leader is lagging behind former HW (KIP-207)
    public const V2_2_1 = 22;

    // Introduced static membership.
    public const V2_3_0 = 23;

    // Add rack_id to FetchRequest, preferred_read_replica to FetchResponse, and replica_id to OffsetsForLeaderRequest
    public const V2_3_1 = 24;

    // Add adding_replicas and removing_replicas fields to LeaderAndIsrRequest
    public const V2_4_0 = 25;

    // Flexible version support in inter-broker APIs
    public const V2_4_1 = 26;

    // No new APIs, equivalent to 2.4.1
    public const V2_5_0 = 27;

    // Introduced StopReplicaRequest V3 containing the leader epoch for each partition (KIP-570)
    public const V2_6_0 = 28;

    // Introduced feature versioning support (KIP-584)
    public const V2_7_0 = 29;

    // Bup Fetch protocol for Raft protocol (KIP-595)
    public const V2_7_1 = 30;

    public static function toString(int $version): ?string
    {
        return [
            self::V0_8_0    => '0.8.0',
            self::V0_8_1    => '0.8.1',
            self::V0_8_2    => '0.8.2',
            self::V0_9_0    => '0.9.0',
            self::V0_10_0_0 => '0.10.0.0',
            self::V0_10_0_1 => '0.10.0.1',
            self::V0_10_1_0 => '0.10.1.0',
            self::V0_10_1_1 => '0.10.1.1',
            self::V0_10_1_2 => '0.10.1.2',
            self::V0_10_2_0 => '0.10.2.0',
            self::V0_11_0_0 => '0.11.0.0',
            self::V0_11_0_1 => '0.11.0.1',
            self::V0_11_0_2 => '0.11.0.2',
            self::V1_0_0    => '1.0.0',
            self::V1_1_0    => '1.1.0',
            self::V2_0_0    => '2.0.0',
            self::V2_0_1    => '2.0.1',
            self::V2_1_0    => '2.1.0',
            self::V2_1_1    => '2.1.1',
            self::V2_1_2    => '2.1.2',
            self::V2_2_0    => '2.2.0',
            self::V2_2_1    => '2.2.1',
            self::V2_3_0    => '2.3.0',
            self::V2_3_1    => '2.3.1',
            self::V2_4_0    => '2.4.0',
            self::V2_4_1    => '2.4.1',
            self::V2_5_0    => '2.5.0',
            self::V2_6_0    => '2.6.0',
            self::V2_7_0    => '2.7.0',
            self::V2_7_1    => '2.7.1',
        ][$version] ?? null;
    }
}
