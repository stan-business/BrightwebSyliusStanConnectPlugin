<?php

/*
 * This file was created by developers working at Brightweb, editor of Stan
 * Visit our website https://stan-business.fr
 * For more information, contact jonathan@brightweb.cloud
*/

declare(strict_types=1);

namespace Brightweb\SyliusStanConnectPlugin\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sylius\Component\Core\Factory\AddressFactoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use SM\Factory\FactoryInterface as StateMachineFactoryInterface;
use Sylius\Component\Core\Repository\CustomerRepositoryInterface;
use Sylius\Component\Core\OrderCheckoutTransitions;
use Sylius\Component\Order\Context\CartContextInterface;
use Doctrine\Persistence\ObjectManager;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\AddressInterface;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

use Brightweb\SyliusStanConnectPlugin\Api\ConnectUserApiInterface;

use Stan\Model\User as StanUser;
use Stan\Model\Address as StanUserAddress;
use Stan\ApiException;

final class ConnectController
{

    private UrlGeneratorInterface $router;

    private CartContextInterface $cartContext;

    private AddressFactoryInterface $addressFactory;

    private FactoryInterface $customerFactory;

    private StateMachineFactoryInterface $stateMachineFactory;

    private ObjectManager $orderManager;

    private CustomerRepositoryInterface $customerRepository;

    private ConnectUserApiInterface $stanConnectApi;

    public function __construct(
        UrlGeneratorInterface $router,
        CartContextInterface $cartContext,
        AddressFactoryInterface $addressFactory,
        FactoryInterface $customerFactory,
        StateMachineFactoryInterface $stateMachineFactory,
        ObjectManager $orderManager,
        CustomerRepositoryInterface $customerRepository,
        ConnectUserApiInterface $stanConnectApi
    ) {
        $this->router = $router;
        $this->addressFactory = $addressFactory;
        $this->customerFactory = $customerFactory;
        $this->stateMachineFactory = $stateMachineFactory;
        $this->orderManager = $orderManager;
        $this->cartContext = $cartContext;
        $this->customerRepository = $customerRepository;
        $this->stanConnectApi = $stanConnectApi;
    }

    public function connectUserWithAuthorizationCode(Request $request): Response
    {
        $order = $this->cartContext->getCart();

        $err = $request->query->get('error');
        if (null !== $err) {
            // TODO logs
            $this->renderError($request, $err);
            return new RedirectResponse($this->router->generate('sylius_shop_checkout_address'));
        }

        $code = $request->query->get('code');

        if (null === $code) {
            $this->renderError($request, 'access_denied');
            return new RedirectResponse($this->router->generate('sylius_shop_checkout_address'));
        }

        try {
            /** @var Stanuser $user */
            $user = $this->stanConnectApi->getUserWithAuthorizationCode($code);

            /** @var CustomerInterface|null $customer */
            $customer = $order->getCustomer();

            if (null === $customer) {
                $customer = $this->getOrderCustomer($user);
                $order->setCustomer($customer);
            }

            $address = $this->getCustomerAddress($customer, $user->getShippingAddress());

            $order->setBillingAddress(clone $address);
            $order->setShippingAddress(clone $address);

            $stateMachine = $this->stateMachineFactory->get($order, OrderCheckoutTransitions::GRAPH);

            if ($order->isShippingRequired()) {
                $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);
            }

            $order->setShippingAddress(clone $address);
            $order->setBillingAddress(clone $address);

            $stateMachine->apply(OrderCheckoutTransitions::TRANSITION_SELECT_SHIPPING);

            $this->orderManager->flush();

            return new RedirectResponse($this->router->generate('sylius_shop_checkout_select_shipping'));
        } catch(ApiException $e) {
            return new RedirectResponse($this->router->generate('sylius_shop_checkout_address', array(
                'stan_connect_error' => 'server_error'
            )));
        }
    }

    private function getOrderCustomer(StanUser $user): CustomerInterface
    {
        /** @var CustomerInterface|null $existingCustomer */
        $existingCustomer = $this->customerRepository->findOneBy(['email' => $user->getEmail()]);
        if (null !== $existingCustomer) {
            return $existingCustomer;
        }

        /** @var CustomerInterface $customer */
        $customer = $this->customerFactory->createNew();
        $customer->setEmail($user->getEmail());
        $customer->setFirstName($user->getGivenName());
        $customer->setLastName($user->getFamilyName());
        $customer->setPhoneNumber($user->getPhone());

        return $customer;
    }

    private function getCustomerAddress(CustomerInterface $customer, StanUserAddress $stanAddress): AddressInterface
    {
        $address = $this->addressFactory->createForCustomer($customer);

        $address->setFirstName($stanAddress->getFirstname());
        $address->setLastName($stanAddress->getLastname());
        $address->setStreet($stanAddress->getStreetAddress());
        $address->setCity($stanAddress->getLocality());
        $address->setPostcode($stanAddress->getZipCode());
        $address->setPhoneNumber($stanAddress->getPhone());

        return $address;
    }

    private function renderError(Request $request, string $err) {
        if (null !== $err) {
            /** @var FlashBagInterface $flashBag */
            $flashBag = $request->getSession()->getBag('flashes');
            $flashBag->add('error', 'brightweb.stan_connect.ui.error.auth_error');
        }
    }
}
