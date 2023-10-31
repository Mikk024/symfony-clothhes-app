<?php

namespace App\Tests\Controller\Men;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_men_shoes_route_is_successfull(): void
    {   
        $this->client->request('GET', '/mens-shoes');

        $this->assertResponseIsSuccessful();
    }

    public function test_men_clothes_route_is_successfull(): void
    {   
        $this->client->request('GET', '/mens-clothing');

        $this->assertResponseIsSuccessful();
    }

    public function test_men_brands_route_is_successfull(): void
    {   
        $this->client->request('GET', '/mens-brands');

        $this->assertResponseIsSuccessful();
    }

    public function test_men_products_route_is_successfull(): void
    {   
        $this->client->request('GET', '/mens-products');

        $this->assertResponseIsSuccessful();
    }
}
