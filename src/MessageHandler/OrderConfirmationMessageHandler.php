<?php

namespace App\MessageHandler;

use App\Message\OrderConfirmationMessage;
use Symfony\Bridge\Twig\Mime\TemplatedEmail as Email ;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class OrderConfirmationMessageHandler{

    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }


    public function __invoke(OrderConfirmationMessage $message)
    {
        $orderId = $message->getOrder()->getId();

        $orderEmail = $message->getOrder()->getUser()->getEmail();

        $email = (new Email())
            ->from('rainbowman230@gmail.com')
            ->to($orderEmail)
            ->subject('Order ' . $orderId)
            ->htmlTemplate('email/order_confirmation.html.twig')
            ->context([
                'order' => $message->getOrder(),
                'address' => $message->getOrder()->getUser()->getAddress()
            ])
        ;

        $this->mailer->send($email);
    }
}
