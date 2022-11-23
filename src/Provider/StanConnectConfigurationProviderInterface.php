<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
 */

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Provider;

use Sylius\Component\Core\Model\ChannelInterface;

interface StanConnectConfigurationProviderInterface
{
    public function getClientId(ChannelInterface $channel): string;
}
