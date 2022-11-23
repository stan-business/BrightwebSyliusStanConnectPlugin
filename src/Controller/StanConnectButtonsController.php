<?php

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Controller;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

use Sylius\PayPalPlugin\Provider\StanConnectConfigurationProviderInterface;

class StanConnectButtonsController
{
    private Environment $twig;

    private UrlGeneratorInterface $router;

    private ChannelContextInterface $channelContext;

    private LocaleContextInterface $localeContext;

    private OrderRepositoryInterface $orderRepository;

    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $router,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        OrderRepositoryInterface $orderRepository
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->orderRepository = $orderRepository;
    }

    public function renderAddressingButton(Request $request): Response
    {
        try {
            return new Response($this->twig->render('@BrightwebSyliusStanConnectPlugin/stan_connect_button.html.twig', []));
        } catch (\InvalidArgumentException $exception) {
            return new Response('');
        }
    }
}
