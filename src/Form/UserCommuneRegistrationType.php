<?php

namespace App\Form;

use App\Entity\UserCommuneRegistration;
use App\Entity\Commune;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class UserCommuneRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $i=0;
        foreach ($options['data'] as $commune)
        {
            $communes[$i]['name'] = $commune->getName();
            $communes[$i]['id'] = $commune->getId();
            $i++;
        }
        $builder
            ->add('communes', EntityType::class, [
                // looks for choices from this entity
                'class' => Commune::class,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'label'=>false,
                'attr' => $communes
            ])
            ->add('user_name',TextType::class,['label'=>false])
            ->add('email', TextType::class,['label'=>false])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label'=>false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label'=>false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null
        ]);
        $resolver->setAllowedTypes('data', 'array');
    }
}