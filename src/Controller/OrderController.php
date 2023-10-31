<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Payment;
use App\Manager\CartManager;
use App\Message\OrderConfirmationMessage;
use App\Security\Voter\OrderVoter;
use App\Service\PaymentService;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class OrderController extends AbstractController
{

    #[Route('/order', name: 'order', methods: ['GET'])]
    public function index(CartManager $cartManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $cart = $cartManager->getCurrentCart();

        if (!$authorizationChecker->isGranted(OrderVoter::ORDER, $cart)) {
            throw new AccessDeniedException("You don't have permission to place this order");
        }

        if (count($cart->getItems()) < 1) {
            $this->addFlash('errors', 'Your cart is empty!');
            return $this->redirectToRoute('home-men');
        }

        $address = $cart->getUser()->getAddress();

        return $this->render('order/index.html.twig', [
            'cart' => $cart,
            'address' => $address
        ]);
    }

    #[Route('/order-handle', name: 'handle-order', methods: ['POST'])]
    public function placeOrder(CartManager $cartManager, PaymentService $paymentService, SessionInterface $session, AuthorizationCheckerInterface $authorizationChecker)
    {
        $cart = $cartManager->getCurrentCart();

        if (!$authorizationChecker->isGranted(OrderVoter::ORDER, $cart)) {
            throw new AccessDeniedException("You don't have permission to place this order");
        }

        $token = bin2hex(random_bytes(16));

        $session->set('payment_success', $token);

        $successUrl = $this->generateUrl('payment_success', [$cart, 'token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

        $cancelUrl = $this->generateUrl('payment_cancel', [], UrlGeneratorInterface::ABSOLUTE_URL);

        $strpeSession = $paymentService->processPayment($cart, $successUrl, $cancelUrl);

        return $this->redirect($strpeSession->url, 303);
    }

    #[Route('/payment/success', name: 'payment_success')]
    public function success(EntityManagerInterface $entityManager, Request $request, CartManager $cartManager, SessionInterface $session, MessageBusInterface $bus)
    {
        $token = $request->query->get('token');

        $cart = $cartManager->getCurrentCart();

        if ($token !== $session->get('payment_success')) {
            $this->addFlash('errors', "You can't access this page");
            return $this->redirectToRoute('home-men');
        }

        foreach ($cart->getItems() as $item) {
            $product = $item->getProduct();
            $productQuantity = $product->getStockQuantity();
            $orderQuantity = $item->getQuantity();
            $finalQuantity = $productQuantity - $orderQuantity;
            $product->setStockQuantity($finalQuantity);
            $entityManager->persist($product);
        }

        $totalPrice = $cart->getTotal();

        $payment = new Payment();
        $payment->setOrderRef($cart);
        $payment->setAmount($totalPrice);
        $payment->setPaymentMethod(Payment::PAYMENT_CARD);

        $cart->setStatus(Order::STATUS_FINALIZED);

        $entityManager->persist($payment);
        $entityManager->persist($cart);
        $entityManager->flush();

        $bus->dispatch(new OrderConfirmationMessage($cart));

        return $this->render('order/success.html.twig');
    }

    #[Route('/payment/cancel', name: 'payment_cancel')]
    public function cancel(): Response
    {
        return $this->render('order/cancel.html.twig');
    }

    #[Route('/order/{id}', name: 'show-order')]
    public function showOrder(Order $order, AuthorizationCheckerInterface $authorizationChecker)
    {
        if (!$authorizationChecker->isGranted(OrderVoter::VIEW_ORDER, $order)) {
            $this->addFlash('errors', "You don't have access to this order");
            return $this->redirectToRoute('list-orders');
        }

        

        return $this->render('order/show.html.twig', [
            'order' => $order,
            'address' => $order->getUser()->getAddress()
        ]);
    }

    #[Route('/list-orders', name: 'list-orders')]
    public function listOrders(EntityManagerInterface $entityManager, PaginatorInterface $paginator, Request $request)
    {
        $userId = $this->getUser()->getId();

        $orders = $entityManager->getRepository(Order::class)
            ->findUserFinalizedOrders($userId);

        $pagination = $paginator->paginate($orders, $request->query->getInt('page', 1), 10);

        return $this->render('order/orders.html.twig', [
            'orders' => $pagination
        ]);
    }

}
