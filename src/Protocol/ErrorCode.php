<?php

declare(strict_types=1);

namespace longlang\phpkafka\Protocol;

use longlang\phpkafka\Exception\KafkaErrorException;

class ErrorCode
{
    public const UNKNOWN_SERVER_ERROR = -1;

    public const NONE = 0;

    public const OFFSET_OUT_OF_RANGE = 1;

    public const CORRUPT_MESSAGE = 2;

    public const UNKNOWN_TOPIC_OR_PARTITION = 3;

    public const INVALID_FETCH_SIZE = 4;

    public const LEADER_NOT_AVAILABLE = 5;

    public const NOT_LEADER_OR_FOLLOWER = 6;

    public const REQUEST_TIMED_OUT = 7;

    public const BROKER_NOT_AVAILABLE = 8;

    public const REPLICA_NOT_AVAILABLE = 9;

    public const MESSAGE_TOO_LARGE = 10;

    public const STALE_CONTROLLER_EPOCH = 11;

    public const OFFSET_METADATA_TOO_LARGE = 12;

    public const NETWORK_EXCEPTION = 13;

    public const COORDINATOR_LOAD_IN_PROGRESS = 14;

    public const COORDINATOR_NOT_AVAILABLE = 15;

    public const NOT_COORDINATOR = 16;

    public const INVALID_TOPIC_EXCEPTION = 17;

    public const RECORD_LIST_TOO_LARGE = 18;

    public const NOT_ENOUGH_REPLICAS = 19;

    public const NOT_ENOUGH_REPLICAS_AFTER_APPEND = 20;

    public const INVALID_REQUIRED_ACKS = 21;

    public const ILLEGAL_GENERATION = 22;

    public const INCONSISTENT_GROUP_PROTOCOL = 23;

    public const INVALID_GROUP_ID = 24;

    public const UNKNOWN_MEMBER_ID = 25;

    public const INVALID_SESSION_TIMEOUT = 26;

    public const REBALANCE_IN_PROGRESS = 27;

    public const INVALID_COMMIT_OFFSET_SIZE = 28;

    public const TOPIC_AUTHORIZATION_FAILED = 29;

    public const GROUP_AUTHORIZATION_FAILED = 30;

    public const CLUSTER_AUTHORIZATION_FAILED = 31;

    public const INVALID_TIMESTAMP = 32;

    public const UNSUPPORTED_SASL_MECHANISM = 33;

    public const ILLEGAL_SASL_STATE = 34;

    public const UNSUPPORTED_VERSION = 35;

    public const TOPIC_ALREADY_EXISTS = 36;

    public const INVALID_PARTITIONS = 37;

    public const INVALID_REPLICATION_FACTOR = 38;

    public const INVALID_REPLICA_ASSIGNMENT = 39;

    public const INVALID_CONFIG = 40;

    public const NOT_CONTROLLER = 41;

    public const INVALID_REQUEST = 42;

    public const UNSUPPORTED_FOR_MESSAGE_FORMAT = 43;

    public const POLICY_VIOLATION = 44;

    public const OUT_OF_ORDER_SEQUENCE_NUMBER = 45;

    public const DUPLICATE_SEQUENCE_NUMBER = 46;

    public const INVALID_PRODUCER_EPOCH = 47;

    public const INVALID_TXN_STATE = 48;

    public const INVALID_PRODUCER_ID_MAPPING = 49;

    public const INVALID_TRANSACTION_TIMEOUT = 50;

    public const CONCURRENT_TRANSACTIONS = 51;

    public const TRANSACTION_COORDINATOR_FENCED = 52;

    public const TRANSACTIONAL_ID_AUTHORIZATION_FAILED = 53;

    public const SECURITY_DISABLED = 54;

    public const OPERATION_NOT_ATTEMPTED = 55;

    public const KAFKA_STORAGE_ERROR = 56;

    public const LOG_DIR_NOT_FOUND = 57;

    public const SASL_AUTHENTICATION_FAILED = 58;

    public const UNKNOWN_PRODUCER_ID = 59;

    public const REASSIGNMENT_IN_PROGRESS = 60;

    public const DELEGATION_TOKEN_AUTH_DISABLED = 61;

    public const DELEGATION_TOKEN_NOT_FOUND = 62;

    public const DELEGATION_TOKEN_OWNER_MISMATCH = 63;

    public const DELEGATION_TOKEN_REQUEST_NOT_ALLOWED = 64;

    public const DELEGATION_TOKEN_AUTHORIZATION_FAILED = 65;

    public const DELEGATION_TOKEN_EXPIRED = 66;

    public const INVALID_PRINCIPAL_TYPE = 67;

    public const NON_EMPTY_GROUP = 68;

    public const GROUP_ID_NOT_FOUND = 69;

    public const FETCH_SESSION_ID_NOT_FOUND = 70;

    public const INVALID_FETCH_SESSION_EPOCH = 71;

    public const LISTENER_NOT_FOUND = 72;

    public const TOPIC_DELETION_DISABLED = 73;

    public const FENCED_LEADER_EPOCH = 74;

    public const UNKNOWN_LEADER_EPOCH = 75;

    public const UNSUPPORTED_COMPRESSION_TYPE = 76;

    public const STALE_BROKER_EPOCH = 77;

    public const OFFSET_NOT_AVAILABLE = 78;

    public const MEMBER_ID_REQUIRED = 79;

    public const PREFERRED_LEADER_NOT_AVAILABLE = 80;

    public const GROUP_MAX_SIZE_REACHED = 81;

    public const FENCED_INSTANCE_ID = 82;

    public const ELIGIBLE_LEADERS_NOT_AVAILABLE = 83;

    public const ELECTION_NOT_NEEDED = 84;

    public const NO_REASSIGNMENT_IN_PROGRESS = 85;

    public const GROUP_SUBSCRIBED_TO_TOPIC = 86;

    public const INVALID_RECORD = 87;

    public const UNSTABLE_OFFSET_COMMIT = 88;

    public const MESSAGES = [
        self::UNKNOWN_SERVER_ERROR => 'The server experienced an unexpected error when processing the request.',

        self::NONE => '',

        self::OFFSET_OUT_OF_RANGE => 'The requested offset is not within the range of offsets maintained by the server.',

        self::CORRUPT_MESSAGE => 'This message has failed its CRC checksum, exceeds the valid size, has a null key for a compacted topic, or is otherwise corrupt.',

        self::UNKNOWN_TOPIC_OR_PARTITION => 'This server does not host this topic-partition.',

        self::INVALID_FETCH_SIZE => 'The requested fetch size is invalid.',

        self::LEADER_NOT_AVAILABLE => 'There is no leader for this topic-partition as we are in the middle of a leadership election.',

        self::NOT_LEADER_OR_FOLLOWER => 'For requests intended only for the leader, this error indicates that the broker is not the current leader. For requests intended for any replica, this error indicates that the broker is not a replica of the topic partition.',

        self::REQUEST_TIMED_OUT => 'The request timed out.',

        self::BROKER_NOT_AVAILABLE => 'The broker is not available.',

        self::REPLICA_NOT_AVAILABLE => 'The replica is not available for the requested topic-partition. Produce/Fetch requests and other requests intended only for the leader or follower return NOT_LEADER_OR_FOLLOWER if the broker is not a replica of the topic-partition.',

        self::MESSAGE_TOO_LARGE => 'The request included a message larger than the max message size the server will accept.',

        self::STALE_CONTROLLER_EPOCH => 'The controller moved to another broker.',

        self::OFFSET_METADATA_TOO_LARGE => 'The metadata field of the offset request was too large.',

        self::NETWORK_EXCEPTION => 'The server disconnected before a response was received.',

        self::COORDINATOR_LOAD_IN_PROGRESS => 'The coordinator is loading and hence can\'t process requests.',

        self::COORDINATOR_NOT_AVAILABLE => 'The coordinator is not available.',

        self::NOT_COORDINATOR => 'This is not the correct coordinator.',

        self::INVALID_TOPIC_EXCEPTION => 'The request attempted to perform an operation on an invalid topic.',

        self::RECORD_LIST_TOO_LARGE => 'The request included message batch larger than the configured segment size on the server.',

        self::NOT_ENOUGH_REPLICAS => 'Messages are rejected since there are fewer in-sync replicas than required.',

        self::NOT_ENOUGH_REPLICAS_AFTER_APPEND => 'Messages are written to the log, but to fewer in-sync replicas than required.',

        self::INVALID_REQUIRED_ACKS => 'Produce request specified an invalid value for required acks.',

        self::ILLEGAL_GENERATION => 'Specified group generation id is not valid.',

        self::INCONSISTENT_GROUP_PROTOCOL => 'The group member\'s supported protocols are incompatible with those of existing members or first group member tried to join with empty protocol type or empty protocol list.',

        self::INVALID_GROUP_ID => 'The configured groupId is invalid.',

        self::UNKNOWN_MEMBER_ID => 'The coordinator is not aware of this member.',

        self::INVALID_SESSION_TIMEOUT => 'The session timeout is not within the range allowed by the broker (as configured by group.min.session.timeout.ms and group.max.session.timeout.ms).',

        self::REBALANCE_IN_PROGRESS => 'The group is rebalancing, so a rejoin is needed.',

        self::INVALID_COMMIT_OFFSET_SIZE => 'The committing offset data size is not valid.',

        self::TOPIC_AUTHORIZATION_FAILED => 'Topic authorization failed.',

        self::GROUP_AUTHORIZATION_FAILED => 'Group authorization failed.',

        self::CLUSTER_AUTHORIZATION_FAILED => 'Cluster authorization failed.',

        self::INVALID_TIMESTAMP => 'The timestamp of the message is out of acceptable range.',

        self::UNSUPPORTED_SASL_MECHANISM => 'The broker does not support the requested SASL mechanism.',

        self::ILLEGAL_SASL_STATE => 'Request is not valid given the current SASL state.',

        self::UNSUPPORTED_VERSION => 'The version of API is not supported.',

        self::TOPIC_ALREADY_EXISTS => 'Topic with this name already exists.',

        self::INVALID_PARTITIONS => 'Number of partitions is below 1.',

        self::INVALID_REPLICATION_FACTOR => 'Replication factor is below 1 or larger than the number of available brokers.',

        self::INVALID_REPLICA_ASSIGNMENT => 'Replica assignment is invalid.',

        self::INVALID_CONFIG => 'Configuration is invalid.',

        self::NOT_CONTROLLER => 'This is not the correct controller for this cluster.',

        self::INVALID_REQUEST => 'This most likely occurs because of a request being malformed by the client library or the message was sent to an incompatible broker. See the broker logs for more details.',

        self::UNSUPPORTED_FOR_MESSAGE_FORMAT => 'The message format version on the broker does not support the request.',

        self::POLICY_VIOLATION => 'Request parameters do not satisfy the configured policy.',

        self::OUT_OF_ORDER_SEQUENCE_NUMBER => 'The broker received an out of order sequence number.',

        self::DUPLICATE_SEQUENCE_NUMBER => 'The broker received a duplicate sequence number.',

        self::INVALID_PRODUCER_EPOCH => 'Producer attempted an operation with an old epoch. Either there is a newer producer with the same transactionalId, or the producer\'s transaction has been expired by the broker.',

        self::INVALID_TXN_STATE => 'The producer attempted a transactional operation in an invalid state.',

        self::INVALID_PRODUCER_ID_MAPPING => 'The producer attempted to use a producer id which is not currently assigned to its transactional id.',

        self::INVALID_TRANSACTION_TIMEOUT => 'The transaction timeout is larger than the maximum value allowed by the broker (as configured by transaction.max.timeout.ms).',

        self::CONCURRENT_TRANSACTIONS => 'The producer attempted to update a transaction while another concurrent operation on the same transaction was ongoing.',

        self::TRANSACTION_COORDINATOR_FENCED => 'Indicates that the transaction coordinator sending a WriteTxnMarker is no longer the current coordinator for a given producer.',

        self::TRANSACTIONAL_ID_AUTHORIZATION_FAILED => 'Transactional Id authorization failed.',

        self::SECURITY_DISABLED => 'Security features are disabled.',

        self::OPERATION_NOT_ATTEMPTED => 'The broker did not attempt to execute this operation. This may happen for batched RPCs where some operations in the batch failed, causing the broker to respond without trying the rest.',

        self::KAFKA_STORAGE_ERROR => 'Disk error when trying to access log file on the disk.',

        self::LOG_DIR_NOT_FOUND => 'The user-specified log directory is not found in the broker config.',

        self::SASL_AUTHENTICATION_FAILED => 'SASL Authentication failed.',

        self::UNKNOWN_PRODUCER_ID => 'This exception is raised by the broker if it could not locate the producer metadata associated with the producerId in question. This could happen if, for instance, the producer\'s records were deleted because their retention time had elapsed. Once the last records of the producerId are removed, the producer\'s metadata is removed from the broker, and future appends by the producer will return this exception.',

        self::REASSIGNMENT_IN_PROGRESS => 'A partition reassignment is in progress.',

        self::DELEGATION_TOKEN_AUTH_DISABLED => 'Delegation Token feature is not enabled.',

        self::DELEGATION_TOKEN_NOT_FOUND => 'Delegation Token is not found on server.',

        self::DELEGATION_TOKEN_OWNER_MISMATCH => 'Specified Principal is not valid Owner/Renewer.',

        self::DELEGATION_TOKEN_REQUEST_NOT_ALLOWED => 'Delegation Token requests are not allowed on PLAINTEXT/1-way SSL channels and on delegation token authenticated channels.',

        self::DELEGATION_TOKEN_AUTHORIZATION_FAILED => 'Delegation Token authorization failed.',

        self::DELEGATION_TOKEN_EXPIRED => 'Delegation Token is expired.',

        self::INVALID_PRINCIPAL_TYPE => 'Supplied principalType is not supported.',

        self::NON_EMPTY_GROUP => 'The group is not empty.',

        self::GROUP_ID_NOT_FOUND => 'The group id does not exist.',

        self::FETCH_SESSION_ID_NOT_FOUND => 'The fetch session ID was not found.',

        self::INVALID_FETCH_SESSION_EPOCH => 'The fetch session epoch is invalid.',

        self::LISTENER_NOT_FOUND => 'There is no listener on the leader broker that matches the listener on which metadata request was processed.',

        self::TOPIC_DELETION_DISABLED => 'Topic deletion is disabled.',

        self::FENCED_LEADER_EPOCH => 'The leader epoch in the request is older than the epoch on the broker.',

        self::UNKNOWN_LEADER_EPOCH => 'The leader epoch in the request is newer than the epoch on the broker.',

        self::UNSUPPORTED_COMPRESSION_TYPE => 'The requesting client does not support the compression type of given partition.',

        self::STALE_BROKER_EPOCH => 'Broker epoch has changed.',

        self::OFFSET_NOT_AVAILABLE => 'The leader high watermark has not caught up from a recent leader election so the offsets cannot be guaranteed to be monotonically increasing.',

        self::MEMBER_ID_REQUIRED => 'The group member needs to have a valid member id before actually entering a consumer group.',

        self::PREFERRED_LEADER_NOT_AVAILABLE => 'The preferred leader was not available.',

        self::GROUP_MAX_SIZE_REACHED => 'The consumer group has reached its max size.',

        self::FENCED_INSTANCE_ID => 'The broker rejected this static consumer since another consumer with the same group.instance.id has registered with a different member.id.',

        self::ELIGIBLE_LEADERS_NOT_AVAILABLE => 'Eligible topic partition leaders are not available.',

        self::ELECTION_NOT_NEEDED => 'Leader election not needed for topic partition.',

        self::NO_REASSIGNMENT_IN_PROGRESS => 'No partition reassignment is in progress.',

        self::GROUP_SUBSCRIBED_TO_TOPIC => 'Deleting offsets of a topic is forbidden while the consumer group is actively subscribed to it.',

        self::INVALID_RECORD => 'This record has failed the validation on broker and hence will be rejected.',

        self::UNSTABLE_OFFSET_COMMIT => 'There are unstable offsets that need to be cleared.',
    ];

    private function __construct()
    {
    }

    public static function getMessage(int $code): string
    {
        return self::MESSAGES[$code] ?? 'Unknown';
    }

    public static function check(int $code): bool
    {
        if (self::NONE !== $code) {
            throw new KafkaErrorException(sprintf('[%s] %s', $code, self::getMessage($code)), $code);
        }

        return true;
    }

    public static function success(int $code): bool
    {
        return self::NONE === $code;
    }

    public static function canRetry(int $code): bool
    {
        return \in_array($code, [
            self::COORDINATOR_LOAD_IN_PROGRESS,
            self::COORDINATOR_NOT_AVAILABLE,
            self::CORRUPT_MESSAGE,
            self::FETCH_SESSION_ID_NOT_FOUND,
            self::INVALID_FETCH_SESSION_EPOCH,
            self::NOT_CONTROLLER,
            self::NOT_COORDINATOR,
            self::NOT_ENOUGH_REPLICAS_AFTER_APPEND,
            self::NOT_ENOUGH_REPLICAS,
            self::OFFSET_NOT_AVAILABLE,
            self::REQUEST_TIMED_OUT,
            self::UNKNOWN_LEADER_EPOCH,
            self::UNSTABLE_OFFSET_COMMIT,
            self::ELECTION_NOT_NEEDED,
            self::ELIGIBLE_LEADERS_NOT_AVAILABLE,
            self::FENCED_LEADER_EPOCH,
            self::KAFKA_STORAGE_ERROR,
            self::LEADER_NOT_AVAILABLE,
            self::LISTENER_NOT_FOUND,
            self::NETWORK_EXCEPTION,
            self::PREFERRED_LEADER_NOT_AVAILABLE,
            self::REPLICA_NOT_AVAILABLE,
            self::UNKNOWN_TOPIC_OR_PARTITION,
        ]);
    }
}
