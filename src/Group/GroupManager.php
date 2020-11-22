<?php

declare(strict_types=1);

namespace longlang\phpkafka\Group;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Group\Struct\ConsumerGroupMemberAssignment;
use longlang\phpkafka\Group\Struct\ConsumerGroupTopic;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\FindCoordinator\FindCoordinatorRequest;
use longlang\phpkafka\Protocol\FindCoordinator\FindCoordinatorResponse;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupRequest;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponse;
use longlang\phpkafka\Protocol\LeaveGroup\LeaveGroupRequest;
use longlang\phpkafka\Protocol\LeaveGroup\LeaveGroupResponse;
use longlang\phpkafka\Protocol\LeaveGroup\MemberIdentity;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequest;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupRequestAssignment;
use longlang\phpkafka\Protocol\SyncGroup\SyncGroupResponse;

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

    public function findCoordinator(string $key, int $keyType = CoordinatorType::GROUP): FindCoordinatorResponse
    {
        $request = new FindCoordinatorRequest();
        $request->setKey($key);
        $request->setKeyType($keyType);

        /** @var FindCoordinatorResponse $response */
        $response = $this->client->sendRecv($request);
        ErrorCode::check($response->getErrorCode());

        return $response;
    }

    public function joinGroup(string $groupId, string $memberId, string $protocolType, ?string $groupInstanceId = null, array $protocols = [], int $sessionTimeoutMs = 60000, int $rebalanceTimeoutMs = -1): JoinGroupResponse
    {
        $request = new JoinGroupRequest();
        $request->setGroupId($groupId);
        $request->setGroupInstanceId($groupInstanceId);
        $request->setMemberId($memberId);
        $request->setProtocolType($protocolType);
        $request->setProtocols($protocols);
        $request->setSessionTimeoutMs($sessionTimeoutMs);
        $request->setRebalanceTimeoutMs($rebalanceTimeoutMs);

        /** @var JoinGroupResponse $response */
        $response = $this->client->sendRecv($request);
        ErrorCode::check($response->getErrorCode());

        return $response;
    }

    public function leaveGroup(string $groupId, string $memberId, ?string $groupInstanceId): LeaveGroupResponse
    {
        $request = new LeaveGroupRequest();
        $request->setGroupId($groupId);
        $request->setMemberId($memberId);
        $request->setMembers([
            (new MemberIdentity())->setMemberId($memberId)->setGroupInstanceId($groupInstanceId),
        ]);

        /** @var LeaveGroupResponse $response */
        $response = $this->client->sendRecv($request);
        ErrorCode::check($response->getErrorCode());

        return $response;
    }

    public function syncGroup(string $groupId, string $groupInstanceId, string $memberId, int $generationId, string $protocolName, string $protocolType, string $topicName, array $partitions): SyncGroupResponse
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

        /** @var SyncGroupResponse $response */
        $response = $this->client->sendRecv($request);
        ErrorCode::check($response->getErrorCode());

        return $response;
    }

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
