<?php

declare(strict_types=1);

namespace Longyan\Kafka\Group;

use Longyan\Kafka\Client\ClientInterface;
use Longyan\Kafka\Protocol\ErrorCode;
use Longyan\Kafka\Protocol\JoinGroup\JoinGroupRequest;
use Longyan\Kafka\Protocol\JoinGroup\JoinGroupRequestProtocol;
use Longyan\Kafka\Protocol\JoinGroup\JoinGroupResponse;
use Longyan\Kafka\Protocol\LeaveGroup\LeaveGroupRequest;
use Longyan\Kafka\Protocol\LeaveGroup\LeaveGroupResponse;
use Longyan\Kafka\Protocol\LeaveGroup\MemberIdentity;

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
