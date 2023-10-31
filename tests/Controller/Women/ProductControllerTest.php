<?php

namespace App\Tests\Controller\Women;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_women_shoes_route_is_successfull(): void
    {   
        $this->client->request('GET', '/women-shoes');

        $this->assertResponseIsSuccessful();
    }

    public function test_women_clothes_route_is_successfull(): void
    {   
        $this->client->request('GET', '/women-clothing');

        $this->assertResponseIsSuccessful();
    }

    public function test_women_brands_route_is_successfull(): void
    {   
        $this->client->request('GET', '/women-brands');

        $this->assertResponseIsSuccessful();
    }

    public function test_women_products_route_is_successfull(): void
    {   
        $this->client->request('GET', '/women-products');

        $this->assertResponseIsSuccessful();
    }
}
