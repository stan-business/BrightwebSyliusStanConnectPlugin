<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
 */

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Provider;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;
use Webmozart\Assert\Assert;

final class StanConnectConfigurationProvider implements StanConnectConfigurationProviderInterface
{
    public function getClientId(ChannelInterface $channel): string
    {
        // $config = $this->getPayPalPaymentMethodConfig($channel);
        // Assert::keyExists($config, 'client_id');

        return (string) "";
    }
}
