<?php

declare(strict_types=1);

namespace longlang\phpkafka\Group;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Group\Struct\ConsumerGroupTopic;
use longlang\phpkafka\Protocol\FindCoordinator\FindCoordinatorRequest;
use longlang\phpkafka\Protocol\FindCoordinator\FindCoordinatorResponse;
use longlang\phpkafka\Protocol\Heartbeat\HeartbeatRequest;
use longlang\phpkafka\Protocol\Heartbeat\HeartbeatResponse;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupRequest;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponse;
use longlang\phpkafka\Protocol\LeaveGroup\LeaveGroupRequest;
use longlang\phpkafka\Protocol\LeaveGroup\LeaveGroupResponse;
use longlang\phpkafka\Protocol\LeaveGroup\MemberIdentity;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequest;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupResponse;
use longlang\phpkafka\Util\KafkaUtil;

class GroupManager
{
    /**
     * @var ClientInterface
     */
    protected $client;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function findCoordinator(string $key, int $keyType = CoordinatorType::GROUP, int $retry = 0, float $sleep = 0.01): FindCoordinatorResponse
    {
        $request = new FindCoordinatorRequest();
        $request->setKey($key);
        $request->setKeyType($keyType);

        return KafkaUtil::retry($this->client, $request, $retry, $sleep);
    }

    public function joinGroup(string $groupId, string $memberId, string $protocolType, ?string $groupInstanceId = null, array $protocols = [], int $sessionTimeoutMs = 60000, int $rebalanceTimeoutMs = -1, int $retry = 0, float $sleep = 0.01): JoinGroupResponse
    {
        $request = new JoinGroupRequest();
        $request->setGroupId($groupId);
        $request->setGroupInstanceId($groupInstanceId);
        $request->setMemberId($memberId);
        $request->setProtocolType($protocolType);
        $request->setProtocols($protocols);
        $request->setSessionTimeoutMs($sessionTimeoutMs);
        $request->setRebalanceTimeoutMs($rebalanceTimeoutMs);

        return KafkaUtil::retry($this->client, $request, $retry, $sleep);
    }

    public function leaveGroup(string $groupId, string $memberId, ?string $groupInstanceId, int $retry = 0, float $sleep = 0.01): LeaveGroupResponse
    {
        $request = new LeaveGroupRequest();
        $request->setGroupId($groupId);
        $request->setMemberId($memberId);
        $request->setMembers([
            (new MemberIdentity())->setMemberId($memberId)->setGroupInstanceId($groupInstanceId),
        ]);

        return KafkaUtil::retry($this->client, $request, $retry, $sleep);
    }

    public function syncGroup(string $groupId, string $groupInstanceId, string $memberId, int $generationId, string $protocolName, string $protocolType, string $topicName, array $partitions, int $retry = 0, float $sleep = 0.01): SyncGroupResponse
    {
        $request = new SyncGroupRequest();
        $request->setGroupId($groupId);
        $request->setGroupInstanceId($groupInstanceId);
        $request->setMemberId($memberId);
        $request->setGenerationId($generationId);
        $request->setProtocolName($protocolName);
        $request->setProtocolType($protocolType);
        $assignment = new SyncGroupRequestAssignment();
        $consumerGroupMemberAssignment = new ConsumerGroupMemberAssignment();
        $consumerGroupTopic = new ConsumerGroupTopic();
        $consumerGroupTopic->setTopicName($topicName);
        $consumerGroupTopic->setPartitions($partitions);
        $consumerGroupMemberAssignment->setTopics([$consumerGroupTopic]);
        $assignment->setMemberId($memberId);
        $assignment->setAssignment($consumerGroupMemberAssignment->pack());
        $request->setAssignments([
            $assignment,
        ]);

        return KafkaUtil::retry($this->client, $request, $retry, $sleep);
    }

    public function heartbeat(string $groupId, string $groupInstanceId, string $memberId, int $generationId, int $retry = 0, float $sleep = 0.01): HeartbeatResponse
    {
        $request = new HeartbeatRequest();
        $request->setGroupId($groupId);
        $request->setGroupInstanceId($groupInstanceId);
        $request->setGenerationId($generationId);
        $request->setMemberId($memberId);

        return KafkaUtil::retry($this->client, $request, $retry, $sleep);
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
