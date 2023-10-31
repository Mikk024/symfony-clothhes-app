<?php

namespace App\Security\Voter;

use App\Entity\Order;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class OrderVoter extends Voter
{
    public const ORDER = 'order';
    public const VIEW_ORDER = 'view_order';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::ORDER, self::VIEW_ORDER])
            && $subject instanceof \App\Entity\Order;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        return match($attribute) {
            self::ORDER => $this->canOrder($subject, $user),
            self::VIEW_ORDER => $this->canViewOrder($subject, $user)
        };
    }

    private function canOrder(Order $order, User $user): bool
    {
        return $user === $order->getUser();
    }

    private function canViewOrder(Order $order, User $user): bool
    {
        return $user === $order->getUser() || $user->getRoles() === ['ROLE_ADMIN', 'ROLE_USER'] && $order->getStatus() !== Order::STATUS_CART;
    }
}
