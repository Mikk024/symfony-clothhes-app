<?php

namespace App\Controller;

use App\Form\CartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/products/cart', name: 'products-cart')]
    public function index(CartManager $cartManager, Request $request): Response
    {
        if (!$this->getUser()) {
            $this->addFlash('errors', 'You have to be logged in');
            return $this->redirectToRoute('home-men');
        }

        $cart = $cartManager->getCurrentCart();
        $form = $this->createForm(CartType::class, $cart);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $cartManager->save($cart);
            return $this->redirectToRoute('products-cart', status: 302);
        }

        return $this->render('cart/index.html.twig', [
            'cart' => $cart,
            'form' => $form->createView()
        ]);
    }

}
