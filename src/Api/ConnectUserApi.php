<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Api;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Brightweb\SyliusStanConnectPlugin\Client\StanConnectClientInterface;

use Stan\Utils\StanUtils;
use Stan\Model\User;

final class ConnectUserApi implements ConnectUserApiInterface
{

    private UrlGeneratorInterface $router;

    private StanConnectClientInterface $stanConnectClient;

    public function __construct(UrlGeneratorInterface $router, StanConnectClientInterface $stanConnectClient)
    {
        $this->router = $router;
        $this->stanConnectClient = $stanConnectClient;
    }

    public function getUserWithAuthorizationCode(string $code): User
    {
        $accessToken = $this
            ->stanConnectClient
            ->getAccessToken($code, $this->getRedirectUri());

        $user = $this
            ->stanConnectClient
            ->getUser($accessToken);

        return $user;
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
        $scheme = $this->router->getContext()->getScheme();
        $host = $this->router->getContext()->getHost();
        return $scheme . '://' . $host . ':8000/stan-connect';
    }
}
