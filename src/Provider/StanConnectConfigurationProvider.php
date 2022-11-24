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
use Brightweb\SyliusStanConnectPlugin\Doctrine\ORM\StanConnectRepository;
use Webmozart\Assert\Assert;

final class StanConnectConfigurationProvider implements StanConnectConfigurationProviderInterface
{
    private StanConnectRepository $stanConnectConfigRepository;

    public function __construct(StanConnectRepository $stanConnectConfigRepository)
    {
        $this->stanConnectConfigRepository = $stanConnectConfigRepository;
    }

    public function getClientId(): string
    {
        $configs = $this->stanConnectConfigRepository->findAll();

        // TODO get the good Stan Connect
        $config = array_pop($configs);

        return $config->getClientId();
    }
}
