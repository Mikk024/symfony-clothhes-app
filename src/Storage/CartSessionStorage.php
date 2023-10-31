<?php

namespace App\Storage;

use App\Entity\Order;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\OrderRepository;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartSessionStorage {
    
    /**
     * 
     * @var RequestStack
     */
    private $requestStack;

    /**
     * 
     * @var OrderRepository
     */
    private $orderRepository;

    /**
     * @var string
     */
    const CART_KEY_NAME = 'cart_id';

    /**
     * 
     * @param RequestStack
     * @param OrderRepository
     */
    public function __construct(
        RequestStack $requestStack,
        OrderRepository $orderRepository
    )
    {
        $this->requestStack = $requestStack;
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return SessionInterface
     */
    public function getSession(): SessionInterface
    {
        return $this->requestStack->getSession();
    }

    /**
     * @return int|null
     */
    public function getCartId(): ?int
    {
        return $this->getSession()->get(self::CART_KEY_NAME);
    }

    /**
     * @param Order $cart
     */
    public function setCart(Order $cart): void
    {
        $this->getSession()->set(self::CART_KEY_NAME, $cart->getId());
    }

    /**
     * 
     * @return Order|null
     */
    public function getCart(): ?Order
    {
        return $this->orderRepository->findOneBy([
            'id' => $this->getCartId(),
            'status' => Order::STATUS_CART
        ]);
    }
}



?>