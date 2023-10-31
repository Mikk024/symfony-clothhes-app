<?php

namespace App\Manager;

use App\Entity\Order;
use App\Entity\User;
use App\Storage\CartSessionStorage;
use App\Factory\OrderFactory;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;

class CartManager {

    /**
     * @var CartSessionStorage $cartSessionStorage
     */
    private $cartSessionStorage;

    /**
     * @var OrderFactory $orderFactory
     */
    private $orderFactory;

    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    private $security;


    /**
     * @param CartSessionStorage $cartSessionStorage
     * @param OrderFactory $orderFactory
     */
    public function __construct(
        CartSessionStorage $cartSessionStorage,
        OrderFactory $orderFactory,
        EntityManagerInterface $entityManager,
        Security $security
    )
    {
        $this->cartSessionStorage = $cartSessionStorage;
        $this->orderFactory = $orderFactory;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }

    public function getCurrentCart(): Order
    {
        $cart = $this->cartSessionStorage->getCart();

        if (!$cart) {
            $cart = $this->orderFactory->create();
        }

        $user = $this->security->getUser();

        if (!$user) {
            throw new AccessDeniedException('User must be logged in to add item to cart');
        }

        $cart->setUser($user);

        return $cart;
    }

    /**
     * @param Order $cart
     */
    public function save(Order $cart): void
    {
        $this->entityManager->persist($cart);
        $this->entityManager->flush();

        $this->cartSessionStorage->setCart($cart);
    }

}



?>