<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = static::getContainer()->get(UserRepository::class);

        /** @var User $user */
        $testUser = $userRepository->findOneByEmail('jane_admin@boris555.de');

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

        $this->assertResponseRedirects('http://shopping2.local/de/login', 302,
            'Redirect from menu to login for unauthorized users is working');
    }
}
