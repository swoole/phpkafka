<?php

declare(strict_types=1);

namespace longlang\phpkafka\Group;

use longlang\phpkafka\Client\ClientInterface;
use longlang\phpkafka\Protocol\ErrorCode;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupRequest;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupRequestProtocol;
use longlang\phpkafka\Protocol\JoinGroup\JoinGroupResponse;
use longlang\phpkafka\Protocol\LeaveGroup\LeaveGroupRequest;
use longlang\phpkafka\Protocol\LeaveGroup\LeaveGroupResponse;
use longlang\phpkafka\Protocol\LeaveGroup\MemberIdentity;

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

    public function joinGroup(string $groupId, string $memberId, string $protocolType, ?string $groupInstanceId = null, array $protocols = [], int $sessionTimeoutMs = 60000, int $rebalanceTimeoutMs = -1): JoinGroupResponse
    {
        $request = new JoinGroupRequest();
        $request->setGroupId($groupId);
        $request->setGroupInstanceId($groupInstanceId);
        $request->setMemberId($memberId);
        $request->setProtocolType($protocolType);
        $protocolsMap = [];
        foreach ($protocols as $k => $v) {
            $protocolsMap[] = (new JoinGroupRequestProtocol())->setName($k)->setMetadata($v);
        }
        $request->setProtocols($protocolsMap);
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

    public function getClient(): ClientInterface
    {
        return $this->client;
    }
}
