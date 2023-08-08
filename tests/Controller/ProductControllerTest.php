<?php

namespace App\Tests\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Validator\Constraints\DateTime;

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
        $this->assertSelectorExists(".button_white", 'Menu is not working!');
        $this->assertSelectorCount(4, ".button_white", 'Count of menu items is wrong!');
    }

    public function testAddProdukt():void
    {
        $this->client->request('GET', 'http://shopping2.local/en/product/add');

        $this->assertSelectorTextContains('#product_save', 'Save', 'There is no Save Button on add product page!');
        $this->assertPageTitleSame('Add Product','Title of Add product page is wrong');

        $crawler = $this->client->request('GET', 'http://shopping2.local/en/product/add');

        //test the form
        $buttonCrawlerNode = $crawler->selectButton('Save');
        $form = $buttonCrawlerNode->form();

        $this->client->submit($form, [
            'product[name]'    => 'Fabien',
            'product[description]' => 'Symfony rocks!',
            'product[price]' => 1,
            //'product[purchased_at]' => new DateTime('today')
            'product[purchased_at]' => '2023-08-06'
        ]);
        $this->assertResponseIsSuccessful( 'Input form is not working!');
    }
}
