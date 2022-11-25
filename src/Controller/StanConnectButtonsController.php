<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Controller;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

use Brightweb\SyliusStanConnectPlugin\Api\ConnectUserApiInterface;

class StanConnectButtonsController
{
    private Environment $twig;

    private UrlGeneratorInterface $router;

    private ChannelContextInterface $channelContext;

    private LocaleContextInterface $localeContext;

    private ConnectUserApiInterface $stanConnectApi;

    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $router,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        ConnectUserApiInterface $stanConnectApi
    ) {
        $this->twig = $twig;
        $this->router = $router;
        $this->channelContext = $channelContext;
        $this->localeContext = $localeContext;
        $this->stanConnectApi = $stanConnectApi;
    }

    public function renderAddressingButton(Request $request): Response
    {
        try {
            return new Response($this->twig->render('@BrightwebSyliusStanConnectPlugin/stan_connect_button.html.twig', [
                'connect_url' => $this->stanConnectApi->getConnectUrl(),
            ]));
        } catch (\InvalidArgumentException $exception) {
            return new Response('');
        }
    }
}
