<?php

namespace App\Controller\Women;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\Type;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{

    /**
     * @var $entityManager
     */
    private $entityManager;
    

    /**
     * @var PaginatorInterface $paginator
     */
    private $paginator;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PaginatorInterface $paginator
     */
    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->paginator = $paginator;
        $this->entityManager = $entityManager;
    }

    #[Route('/women-shoes', name: 'women-shoes')]
    public function listShoes(Request $request): Response
    {
        $category = $request->query->get('name');

        $brand = $request->query->get('brand');

        $categories = $this->entityManager->getRepository(Type::class)
            ->findOneBy(['name' => 'Shoes'])
            ->getCategories();

        $products = $this->entityManager->getRepository(Product::class)
            ->findProductsByParams('female', 'Shoes', $category, $brand);

        $brands = $this->entityManager->getRepository(Brand::class)
            ->findAll();

        $pagination = $this->paginator->paginate($products,$request->query->getInt('page', 1) , 6);

        return $this->render('women/shoes.html.twig', [
            'products' => $pagination,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    #[Route('/women-clothing', name: 'women-clothes')]
    public function listClothes(Request $request): Response
    {
        $category = $request->query->get('name');

        $brand = $request->query->get('brand');

        $categories = $this->entityManager->getRepository(Type::class)
            ->findOneBy(['name' => 'Clothes'])
            ->getCategories();

        $brands = $this->entityManager->getRepository(Brand::class)
            ->findAll();

        $products = $this->entityManager->getRepository(Product::class)
            ->findProductsByParams('female', 'Clothes', $category, $brand);

        $pagination = $this->paginator->paginate($products,$request->query->getInt('page', 1) , 6);

        return $this->render('women/clothes.html.twig', [
            'products' => $pagination,
            'categories' => $categories,
            'brands' => $brands
        ]);
    }

    #[Route('/women-brands', name: 'women-brands')]
    public function listBrands(): Response
    {
        $brands = $this->entityManager->getRepository(Brand::class)
            ->findAll();

        return $this->render('women/brands.html.twig', [
            'brands' => $brands
        ]);
    }

    #[Route('/women-products', name: 'women-products')]
    public function listProducts(Request $request)
    {
        $brand = $request->query->get('brand');

        $products = $this->entityManager->getRepository(Product::class)
            ->findProductsByParams('female', null, null, $brand);

        $pagination = $this->paginator->paginate($products,$request->query->getInt('page', 1) , 6);

        return $this->render('women/products.html.twig', [
            'products' => $pagination
        ]);
    }

}
