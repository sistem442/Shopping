<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://shopping2.local/products/test');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('small', 'de');
    }

    public function testLogon(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', 'http://shopping2.local/de/menu');

        $this->assertResponseIsSuccessful();
        $this->assertResponseIsSuccessful($message = 'menu is not working');
    }
}
