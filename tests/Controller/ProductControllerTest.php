<?php

namespace App\Tests\Controller;

use App\Entity\User;
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
        yield ['GET', 'http://shopping2.local/de/products/overview/2023-7'];
    }

    public function testProducts(): void
    {
        $this->client->request('GET', 'http://shopping2.local/de/products/overview/2023-7');
        $this->assertResponseIsSuccessful();

        $this->assertSelectorTextContains("select#year > option[selected]",'2023');
        $this->assertSelectorTextContains("select#month > option[selected]",'7');
    }

    public function testMenu():void
    {
        $this->client->request('GET', 'http://shopping2.local/de/menu');
        $this->assertSelectorExists(".pure-menu-item", 'Menu is not working!');
        $this->assertSelectorCount(3, ".pure-menu-item", 'Count of menu items is wrong!');
    }

    public function testAddProdukt():void
    {
        $this->client->request('GET', 'http://shopping2.local/en/product/add');

        $this->assertSelectorTextContains('#product_save', 'Save', 'There is no Save Button on add product page!');
        $this->assertSelectorTextContains('.required', 'Name', 'There is no Name input on add product page!');
        $this->assertPageTitleSame('Add Product','Title of Add product page is wrong');

        $crawler = $this->client->request('GET', 'http://shopping2.local/en/product/add');

        //test the form
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form();

        $this->client->submit($form, [
            'product[name]'    => 'Fabien',
            'product[description]' => 'Symfony rocks!',
        ]);
        $this->assertResponseIsSuccessful( 'Input form is not working!');
    }
}
