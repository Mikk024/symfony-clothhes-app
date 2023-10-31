<?php

namespace App\Service;

use App\Entity\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class PaymentService
{

    private $stripeSK;

    public function __construct($stripeSK)
    {
        $this->stripeSK = $stripeSK;
    }

    public function processPayment(Order $cart, string $successUrl, string $cancelUrl)
    {
        Stripe::setApiKey($this->stripeSK);

        $totalPrice = $cart->getTotal() * 100;

        $session = Session::create([
            'payment_method_types' => ['card'],
            'line_items'           => [
                [
                    'price_data' => [
                        'currency'     => 'usd',
                        'product_data' => [
                            'name' => 'T-shirt',
                        ],
                        'unit_amount'  => $totalPrice,
                    ],
                    'quantity'   => 1,
                ]
            ],
            'mode'                 => 'payment',
            'success_url'          => $successUrl,
            'cancel_url'           => $cancelUrl,
        ]);

        return $session;
    }
}




?>