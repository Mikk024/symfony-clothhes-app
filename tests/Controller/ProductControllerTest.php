<?php

namespace App\Tests\Controller;

use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function test_product_show(): void
    {
        $client = static::createClient();

        $entityManager = $client->getContainer()->get('doctrine');

        $proudctId = $entityManager->getRepository(Product::class)
            ->findOneBy([])->getId();



        $client->request('GET', '/products/' . $proudctId);
        
        $this->assertResponseIsSuccessful();
    }
}
