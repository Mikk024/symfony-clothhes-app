<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use App\Entity\User;
use App\Tests\CartAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class CartControllerTest extends WebTestCase
{
    use CartAssertionsTrait;

    private $client;

    private $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine');
    }

    public function testCartIsEmpty(): void
    {
        $this->client->loginUser($this->getUser());
        $crawler = $this->client->request('GET', '/products/cart');

        $this->assertResponseIsSuccessful();
        $this->assertCartIsEmpty($crawler);
    }

    public function testAddProductToCart()
    {
        $this->client->loginUser($this->getUser());

        $product = $this->addRandomProductToCart($this->client);
        $crawler = $this->client->request('GET', '/products/cart');

        $this->assertResponseIsSuccessful();
        $this->assertCartItemsCountEquals($crawler, 1);
        $this->assertCartTotalEquals($crawler, number_format($product->getPrice() + 9.99, 2) . ' $');
    }

    public function testAddProductToCartTwice()
    {
        $this->client->loginUser($this->getUser());

        $product = $this->getRandomProduct();

        $crawler = $this->client->request('GET', '/products/' . $product->getId());

        for ($i = 0; $i < 2; $i++) {
            $form = $crawler->filter('form')->form();
            $form->setValues(['add_to_cart[quantity]' => 1]);
            $this->client->submit($form);
            $crawler = $this->client->followRedirect();
        }

        $crawler = $this->client->request('GET', '/products/cart');

        $this->assertResponseIsSuccessful();
        $this->assertCartItemsCountEquals($crawler, 1);
        $this->assertCartTotalEquals($crawler, number_format(($product->getPrice() * 2) + 9.99, 2) . ' $');
    }


    public function testClearCart()
    {
        $this->client->loginUser($this->getUser());

        $this->addRandomProductToCart($this->client);
        $this->client->request('GET', '/products/cart');
        $this->client->submitForm('Clear');
        $crawler = $this->client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertCartIsEmpty($crawler);
    }


    private function getUser(): User
    {
        return $this->entityManager->getRepository(User::class)
            ->findOneBy([]);
    }
}
