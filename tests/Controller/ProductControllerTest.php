<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Pagination\Paginator;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    private KernelBrowser $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = static::createClient();

        /** @var UserRepository $userRepository */
        $userRepository = $this->client->getContainer()->get(UserRepository::class);
        /** @var User $user */
        $user = $userRepository->findOneByEmail('jane_admin@boris555.de');
        $this->client->loginUser($user);
    }

    public function getUrlsForRegularUsers(): \Generator
    {
        yield ['GET', 'http://shopping2.local/de/menu'];
        yield ['GET', 'http://shopping2.local/en/product/add'];
        yield ['GET', 'http://shopping2.local/en/products/page/1'];
    }

    public function testProducts(): void
    {
        $crawler = $this->client->request('GET', 'http://shopping2.local/de/products/page/1');

        $this->assertResponseIsSuccessful();

        $this->assertCount(
            Paginator::PAGE_SIZE,
            $crawler->filter('tr.product'),
            'The products displays the right number of products.'
        );
    }
}
