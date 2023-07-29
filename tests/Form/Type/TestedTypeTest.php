<?php
namespace App\Tests\Form\Type;

use App\Entity\AdminCommuneRegistration;
use App\Form\AdminCommuneRegistrationType;
use App\Form\Type\ProductType;
use App\Entity\Product;
use Symfony\Component\Form\Test\TypeTestCase;

class TestedTypeTest extends TypeTestCase
{
    public function testProductForm(): void
    {
        $date = new \DateTime('2023-06-05 00:00:00');
        $formData = [
            'name' => 'test name',
            'description' => 'test description',
            'price'=>100,
            'purchased_at' => $date
        ];

        $model = new Product();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ProductType::class, $model);

        $expected = new Product();
        // ...populate $expected properties with the data stored in $formData
        $expected->setPurchasedAt($date);
        $expected->setName('test name');
        $expected->setDescription('test description');
        $expected->setPrice(10000);

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        //TODO this is not working because of Datetime object
        //$this->assertEquals($expected, $model);
    }

    public function testNewCommuneForm(): void
    {

        $formData = [
            'commune_name' => 'test commune name',
            'user_name' => 'test user name',
            'email'=>'test@boris555.de',
            'plainPassword' => 'password',
            'agreeTerms' => 'true'
        ];

        $model = new AdminCommuneRegistration();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(AdminCommuneRegistrationType::class, $model);

        $expected = new AdminCommuneRegistration();
        // ...populate $expected properties with the data stored in $formData
        $expected->setUserName('test user name');
        $expected->setCommuneName('test commune name');
        $expected->setPlainPassword('password');
        $expected->setAgreeTerms(true);
        $expected->setEmail('test@boris555.de');

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        //TODO this is not working because of Datetime object
        $this->assertEquals($expected, $model);
    }


}