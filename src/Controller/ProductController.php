<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Manager\CartManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/products/{id}', name: 'show-product', requirements: ['id' => "\d+"])]
    public function showProduct(Product $product,CartManager $cartManager, Request $request)
    {
        $form = $this->createForm(AddToCartType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (!$this->getUser()) {
                $this->addFlash('errors', 'You have to be logged in to add item to cart');
                return $this->redirectToRoute('show-product', ['id' => $product->getId()]); 
            }   
            $item = $form->getData();
            $item->setProduct($product);
     
            $cart = $cartManager->getCurrentCart();
            $cart
                ->addItem($item);

            $cartManager->save($cart);

            return $this->redirectToRoute('show-product', ['id' => $product->getId()]); 
        }

        return $this->render('men/show.html.twig', [
            'product' => $product,
            'form' => $form->createView()
        ]);
    }
}
