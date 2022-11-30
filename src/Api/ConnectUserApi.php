<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Api;

use Brightweb\SyliusStanConnectPlugin\Client\StanConnectClientInterface;

use Stan\Utils\StanUtils;

final class ConnectUserApi implements ConnectUserApiInterface
{
    private StanConnectClientInterface $stanConnectClient;

    public function __construct(StanConnectClientInterface $stanConnectClient)
    {
        $this->stanConnectClient = $stanConnectClient;
    }

    public function getUserWithAuthorizationCode(string $code)
    {
        $accessToken = $this
            ->stanConnectClient
            ->getAccessToken($code, $this->getRedirectUri());

        $user = $this
            ->stanConnectClient
            ->getUser($accessToken);
    }

    public function getConnectUrl(): string
    {
        $state = StanUtils::generateState();
        return $this
            ->stanConnectClient
            ->getConnectUrl($this->getRedirectUri(), $state)
        ;
    }

    public function getRedirectUri(): string
    {
        return "TODO"; // TODO
    }
}
