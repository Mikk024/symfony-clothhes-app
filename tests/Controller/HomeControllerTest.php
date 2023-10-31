<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    private $client;

    public function setUp(): void
    {
        $this->client = static::createClient();
    }

    public function test_redirects_to_home_men(): void
    {
        $this->client->request('GET', '/');

        $this->assertResponseRedirects();
    }

    public function test_home_men()
    {
        $this->client->request('GET', '/men');

        $this->assertResponseIsSuccessful();
    }

    public function test_home_women()
    {
        $this->client->request('GET', '/women');

        $this->assertResponseIsSuccessful();
    }
}
