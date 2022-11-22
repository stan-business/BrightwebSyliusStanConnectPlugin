<?php

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

final class Configuration implements ConfigurationInterface
{
    /**
     * @psalm-suppress UnusedVariable
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('brightweb_sylius_stan_connect_plugin');
        $rootNode = $treeBuilder->getRootNode();

        return $treeBuilder;
    }
}
