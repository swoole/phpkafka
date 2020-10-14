<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

abstract class AbstractApiKeys
{
    public const PROTOCOL_PRODUCE = 0;
    public const PROTOCOL_FETCH = 1;
    public const PROTOCOL_LIST_OFFSET = 2;
    public const PROTOCOL_METADATA = 3;
    public const PROTOCOL_LEADER_AND_ISR = 4;
    public const PROTOCOL_STOP_REPLICA = 5;
    public const PROTOCOL_UPDATE_METADATA = 6;
    public const PROTOCOL_CONTROLLED_SHUTDOWN = 7;
    public const PROTOCOL_OFFSET_COMMIT = 8;
    public const PROTOCOL_OFFSET_FETCH = 9;
    public const PROTOCOL_FIND_COORDINATOR = 10;
    public const PROTOCOL_JOIN_GROUP = 11;
    public const PROTOCOL_HEARTBEAT = 12;
    public const PROTOCOL_LEAVE_GROUP = 13;
    public const PROTOCOL_SYNC_GROUP = 14;
    public const PROTOCOL_DESCRIBE_GROUPS = 15;
    public const PROTOCOL_LIST_GROUPS = 16;
    public const PROTOCOL_SASL_HANDSHAKE = 17;
    public const PROTOCOL_API_VERSIONS = 18;
    public const PROTOCOL_CREATE_TOPICS = 19;
    public const PROTOCOL_DELETE_TOPICS = 20;
    public const PROTOCOL_DELETE_RECORDS = 21;
    public const PROTOCOL_INIT_PRODUCER_ID = 22;
    public const PROTOCOL_OFFSET_FOR_LEADER_EPOCH = 23;
    public const PROTOCOL_ADD_PARTITIONS_TO_TXN = 24;
    public const PROTOCOL_ADD_OFFSETS_TO_TXN = 25;
    public const PROTOCOL_END_TXN = 26;
    public const PROTOCOL_WRITE_TXN_MARKERS = 27;
    public const PROTOCOL_TXN_OFFSET_COMMIT = 28;
    public const PROTOCOL_DESCRIBE_ACLS = 29;
    public const PROTOCOL_CREATE_ACLS = 30;
    public const PROTOCOL_DELETE_ACLS = 31;
    public const PROTOCOL_DESCRIBE_CONFIGS = 32;
    public const PROTOCOL_ALTER_CONFIGS = 33;
    public const PROTOCOL_ALTER_REPLICA_LOG_DIRS = 34;
    public const PROTOCOL_DESCRIBE_LOG_DIRS = 35;
    public const PROTOCOL_SASL_AUTHENTICATE = 36;
    public const PROTOCOL_CREATE_PARTITIONS = 37;
    public const PROTOCOL_CREATE_DELEGATION_TOKEN = 38;
    public const PROTOCOL_RENEW_DELEGATION_TOKEN = 39;
    public const PROTOCOL_EXPIRE_DELEGATION_TOKEN = 40;
    public const PROTOCOL_DESCRIBE_DELEGATION_TOKEN = 41;
    public const PROTOCOL_DELETE_GROUPS = 42;
    public const PROTOCOL_ELECT_LEADERS = 43;
    public const PROTOCOL_INCREMENTAL_ALTER_CONFIGS = 44;
    public const PROTOCOL_ALTER_PARTITION_REASSIGNMENTS = 45;
    public const PROTOCOL_LIST_PARTITION_REASSIGNMENTS = 46;
    public const PROTOCOL_OFFSET_DELETE = 47;
    public const PROTOCOL_DESCRIBE_CLIENT_QUOTAS = 48;
    public const PROTOCOL_ALTER_CLIENT_QUOTAS = 49;

    public const PROTOCOL_MAP = [
        self::PROTOCOL_PRODUCE                       => 'Produce',
        self::PROTOCOL_FETCH                         => 'Fetch',
        self::PROTOCOL_LIST_OFFSET                   => 'ListOffset',
        self::PROTOCOL_METADATA                      => 'Metadata',
        self::PROTOCOL_LEADER_AND_ISR                => 'LeaderAndIsr',
        self::PROTOCOL_STOP_REPLICA                  => 'StopReplica',
        self::PROTOCOL_UPDATE_METADATA               => 'UpdateMetadata',
        self::PROTOCOL_CONTROLLED_SHUTDOWN           => 'ControlledShutdown',
        self::PROTOCOL_OFFSET_COMMIT                 => 'OffsetCommit',
        self::PROTOCOL_OFFSET_FETCH                  => 'OffsetFetch',
        self::PROTOCOL_FIND_COORDINATOR              => 'FindCoordinator',
        self::PROTOCOL_JOIN_GROUP                    => 'JoinGroup',
        self::PROTOCOL_HEARTBEAT                     => 'Heartbeat',
        self::PROTOCOL_LEAVE_GROUP                   => 'LeaveGroup',
        self::PROTOCOL_SYNC_GROUP                    => 'SyncGroup',
        self::PROTOCOL_DESCRIBE_GROUPS               => 'DescribeGroups',
        self::PROTOCOL_LIST_GROUPS                   => 'ListGroups',
        self::PROTOCOL_SASL_HANDSHAKE                => 'SaslHandshake',
        self::PROTOCOL_API_VERSIONS                  => 'ApiVersions',
        self::PROTOCOL_CREATE_TOPICS                 => 'CreateTopics',
        self::PROTOCOL_DELETE_TOPICS                 => 'DeleteTopics',
        self::PROTOCOL_DELETE_RECORDS                => 'DeleteRecords',
        self::PROTOCOL_INIT_PRODUCER_ID              => 'InitProducerId',
        self::PROTOCOL_OFFSET_FOR_LEADER_EPOCH       => 'OffsetForLeaderEpoch',
        self::PROTOCOL_ADD_PARTITIONS_TO_TXN         => 'AddPartitionsToTxn',
        self::PROTOCOL_ADD_OFFSETS_TO_TXN            => 'AddOffsetsToTxn',
        self::PROTOCOL_END_TXN                       => 'EndTxn',
        self::PROTOCOL_WRITE_TXN_MARKERS             => 'WriteTxnMarkers',
        self::PROTOCOL_TXN_OFFSET_COMMIT             => 'TxnOffsetCommit',
        self::PROTOCOL_DESCRIBE_ACLS                 => 'DescribeAcls',
        self::PROTOCOL_CREATE_ACLS                   => 'CreateAcls',
        self::PROTOCOL_DELETE_ACLS                   => 'DeleteAcls',
        self::PROTOCOL_DESCRIBE_CONFIGS              => 'DescribeConfigs',
        self::PROTOCOL_ALTER_CONFIGS                 => 'AlterConfigs',
        self::PROTOCOL_ALTER_REPLICA_LOG_DIRS        => 'AlterReplicaLogDirs',
        self::PROTOCOL_DESCRIBE_LOG_DIRS             => 'DescribeLogDirs',
        self::PROTOCOL_SASL_AUTHENTICATE             => 'SaslAuthenticate',
        self::PROTOCOL_CREATE_PARTITIONS             => 'CreatePartitions',
        self::PROTOCOL_CREATE_DELEGATION_TOKEN       => 'CreateDelegationToken',
        self::PROTOCOL_RENEW_DELEGATION_TOKEN        => 'RenewDelegationToken',
        self::PROTOCOL_EXPIRE_DELEGATION_TOKEN       => 'ExpireDelegationToken',
        self::PROTOCOL_DESCRIBE_DELEGATION_TOKEN     => 'DescribeDelegationToken',
        self::PROTOCOL_DELETE_GROUPS                 => 'DeleteGroups',
        self::PROTOCOL_ELECT_LEADERS                 => 'ElectLeaders',
        self::PROTOCOL_INCREMENTAL_ALTER_CONFIGS     => 'IncrementalAlterConfigs',
        self::PROTOCOL_ALTER_PARTITION_REASSIGNMENTS => 'AlterPartitionReassignments',
        self::PROTOCOL_LIST_PARTITION_REASSIGNMENTS  => 'ListPartitionReassignments',
        self::PROTOCOL_OFFSET_DELETE                 => 'OffsetDelete',
        self::PROTOCOL_DESCRIBE_CLIENT_QUOTAS        => 'DescribeClientQuotas',
        self::PROTOCOL_ALTER_CLIENT_QUOTAS           => 'AlterClientQuotas',
    ];

    private function __construct()
    {
    }
}
