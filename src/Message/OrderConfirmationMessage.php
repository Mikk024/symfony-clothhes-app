<?php

namespace App\Message;

use App\Entity\Order;

final class OrderConfirmationMessage
{

    private $order;
      
    public function __construct(Order $order)
    {
       $this->order = $order;
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
