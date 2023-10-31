<?php

namespace App\Controller\Auth;


use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use App\Form\EditProductType;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use Google\Cloud\Storage\StorageClient;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;


#[IsGranted('ROLE_ADMIN')]
class AdminController extends AbstractController
{
    /**
     * @var $entityManager
     */
    private $entityManager;

    private $paginator;


    /**
     * @param EntityManagerInterface $entityManager;
     */
    public function __construct(EntityManagerInterface $entityManager, PaginatorInterface $paginator)
    {
        $this->entityManager = $entityManager;
        $this->paginator = $paginator;
    }

    #[Route('/admin/store', name: 'admin-store')]
    public function newProduct(Request $request, SluggerInterface $slugger): Response
    {
        $storageClient = new StorageClient([
            'projectId' => $_ENV['GOOGLE_CLOUD_PROJECT'],
            'keyFilePath' => $_ENV['GOOGLE_CLOUD_KEY_FILE'],
        ]);

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('image')->getData();

            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename.'-'.uniqid() . '.'. $file->guessExtension();

            $bucket = $storageClient->bucket($_ENV['GOOGLE_CLOUD_STORAGE_BUCKET']);
            $bucket->upload(fopen($file->getPathName(), 'r'), [
                'name' => 'uploads/' . $newFilename,
            ]);

            unlink($file->getPathname());

            $product->setImage('https://storage.googleapis.com/' . $_ENV['GOOGLE_CLOUD_STORAGE_BUCKET'] . '/uploads/' . $newFilename);
            $this->entityManager->persist($product);
            $this->entityManager->flush();

            return $this->redirectToRoute('admin-store');
        }

        return $this->render('admin/new.html.twig', [
            'productForm' => $form
        ]);
    }

    #[Route('/admin/products', name: 'admin-products')]
    public function listProducts(Request $request)
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $pagination = $this->paginator->paginate($products, $request->query->get('page', 1), 8);
        
        return $this->render('admin/products.html.twig', [
            'products' => $pagination
        ]);
    }

    #[Route('/admin/users', name: 'admin-users')]
    public function listUsers(Request $request)
    {
        $users = $this->entityManager->getRepository(User::class)
            ->findAll();

        $pagination = $this->paginator->paginate($users, $request->query->get('page', 1), 10);

        return $this->render('admin/users.html.twig', [
            'users' => $pagination
        ]);
    }

    #[Route('/admin/orders', name: 'admin-orders')]
    public function listOrders(Request $request)
    {
        $orders = $this->entityManager->getRepository(Order::class)
            ->findAllFinalizedOrders();

        $pagination = $this->paginator->paginate($orders, $request->query->get('page', 1), 10);

        return $this->render('admin/orders.html.twig', [
            'orders' => $pagination
        ]);
    }

    #[Route('/admin/{id}/update', name: 'admin-update')]
    public function updateProduct(Request $request, Product $product, EntityManagerInterface $entityManager)
    {
        

        $form = $this->createForm(EditProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute('admin-products');
        }

        return $this->render('admin/update.html.twig', [
            'productForm' => $form->createView()
        ]);
    }

}
?>