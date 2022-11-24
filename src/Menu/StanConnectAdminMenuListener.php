<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Doctrine\Persistence\ManagerRegistry;
use Brightweb\SyliusStanConnectPlugin\Entity\StanConnect;

final class StanConnectAdminMenuListener
{
    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    protected $stanConnectRepository;

    /**
     * @var \Doctrine\Persistence\ObjectManager
     */
    protected $entityManager;

    /**
     * @param ManagerRegistry $doctrine
     */
    public function __construct(
        ManagerRegistry $doctrine
    ) {
        $this->entityManager = $doctrine->getManager();
        $this->stanConnectRepository = $this->entityManager->getRepository(StanConnect::class);
    }

    public function addStanConnectMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $configuration = $menu->getChild("customers");

        $configuration
            ->addChild("stan_connect", [
                "route" => "brightweb_sylius_stan_connect_admin_stan_connect_update",
                "routeParameters" => ["id" => $this->checkConfigExist()]
            ])
            ->setLabel("brightweb.stan_connect.ui.label")
            ->setLabelAttribute("icon", "building");
    }

    /**
     * Get last item, if does not exist - create one
     * @return int
     */
    private function checkConfigExist()
    {
        $config = $this->stanConnectRepository->findOneBy([]);
        if (!$config) {
            $config = new StanConnect();
            $this->entityManager->persist($config);
            $this->entityManager->flush();
        }
        return $config->getId();
    }
}
