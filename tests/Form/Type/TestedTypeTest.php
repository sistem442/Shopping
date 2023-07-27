<?php
namespace App\Tests\Form\Type;

use App\Form\Type\ProductType;
use App\Entity\Product;
use Symfony\Component\Form\Test\TypeTestCase;

class TestedTypeTest extends TypeTestCase
{
    public function testSubmitValidData(): void
    {
        $formData = [
            'Name' => 'test Product test test test',
            'description' => 'Test test test test test test',
            'purchased_at' => '2023-06-05 00:00:00'
        ];

        $model = new Product();
        // $model will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(ProductType::class, $model);

        $expected = new Product();
        // ...populate $expected properties with the data stored in $formData

        // submit the data to the form directly
        $form->submit($formData);

        // This check ensures there are no transformation failures
        $this->assertTrue($form->isSynchronized());

        // check that $model was modified as expected when the form was submitted
        $this->assertEquals($expected, $model);
    }

    public function testCustomFormView(): void
    {
        $formData = new Product();
        // ... prepare the data as you need

        // The initial data may be used to compute custom view variables
        $view = $this->factory->create(Product::class, $formData)
            ->createView();

        $this->assertArrayHasKey('custom_var', $view->vars);
        $this->assertSame('expected value', $view->vars['custom_var']);
    }
}