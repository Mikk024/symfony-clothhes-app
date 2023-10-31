<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AdminControllerTest extends WebTestCase
{
    private $entityManager;

    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine');
    }

    public function test_admin_can_see_all_products(): void
    {
        $this->client->loginUser($this->getAdmin());
        $this->client->request('GET', '/admin/products');

        $this->assertResponseIsSuccessful();
    }

    public function test_user_cannot_see_all_products()
    {
        $this->client->request('GET', '/admin/products');

        $this->assertResponseRedirects();
    }

    public function test_admin_can_see_all_orders()
    {
        $this->client->loginUser($this->getAdmin());
        $this->client->request('GET', '/admin/orders');

        $this->assertResponseIsSuccessful();
    }

    public function test_admin_can_see_all_users()
    {
        $this->client->loginUser($this->getAdmin());
        $this->client->request('GET', '/admin/users');

        $this->assertResponseIsSuccessful();
    }

    public function test_admin_can_add_new_product()
    {
        $imagePath = __DIR__ . '/test-image.jpg';

        $this->client->loginUser($this->getAdmin());
        $crawler = $this->client->request('GET', '/admin/store');
        $this->assertResponseIsSuccessful();
    
        $productCount = $this->entityManager->getRepository(Product::class)->count([]);

        $form = $crawler->selectButton('Add')->form();
        $form['product[name]'] = 'Sample Product';
        $form['product[price]'] = 50;
        $form['product[color]'] = 'Blue';
        $form['product[size]'] = '44';
        $form['product[gender]'] = 'Male';
        $form['product[brand]']->select(1);
        $form['product[stock_quantity]'] = 10;
        $form['product[category]']->select(1);
        $form['product[image]']->upload($imagePath);

        $this->client->submit($form);

        $updatedProductCount = $this->entityManager->getRepository(Product::class)->count([]);

        $this->assertEquals($productCount + 1, $updatedProductCount);
        $this->assertResponseRedirects();
    }

    public function getAdmin()
    {
        return $this->entityManager->getRepository(User::class)
            ->findOneBy(['id' => 1]);
    }
}
