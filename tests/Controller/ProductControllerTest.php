<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $testUser = $userRepository->findOneByEmail('boris.klinko@gmail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $client->request('GET', 'http://shopping2.local/products/test');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('small', 'de');
    }

    public function testLogon(): void
    {
        $client = static::createClient();
        $client->request('GET', 'http://shopping2.local/de/menu');

        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains('body', 'Redirecting to <a href="http://shopping2.local/de/login">http://shopping2.local/de/login</a>.');
    }
}
