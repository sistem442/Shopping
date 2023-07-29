<?php

namespace App\Form;

use App\Entity\AdminCommuneRegistration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AdminCommuneRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('commune_name', TextType::class,['label'=>'Commune name']);
        $builder->add('user_name', TextType::class,['label'=>'User name']);
        $builder->add('email',TextType::class);
        $builder->add('agreeTerms', CheckboxType::class);
        $builder->add('plainPassword', PasswordType::class, [
            'attr' => ['autocomplete' => 'new-password']
        ]);
        $builder->add('save', SubmitType::class);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AdminCommuneRegistration::class
        ]);
    }
}