<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Tests\CartAssertionsTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderControllerTest extends WebTestCase
{
    use CartAssertionsTrait;

    private $client;

    private $entityManager;

    public function setUp(): void 
    {
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine');
    }

    public function testRediectWhenNoItemsAreInCart(): void
    {
        $this->client->loginUser($this->getUser());
        $this->client->request('GET', '/order');

        $this->assertResponseRedirects();
    }

    private function getUser(): User
    {
        return $this->entityManager->getRepository(User::class)
            ->findOneBy([]);
    }
}
