<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('currentPassword', PasswordType::class,
                [
                    'attr' => [
                        'class' => 'block max-w-[625px] w-full bg-gray-50 border border-gray-100 py-3 pl-6 pr-3 rounded-lg'
                    ],
                    'label' => 'Mot de passe actuel',
                    'mapped' => false,
                    'row_attr' => [
                        'class' => 'space-y-2'
                    ]
                ]
            )
            ->add('password', RepeatedType::class,
                [
                    'type' => PasswordType::class,
                    'options' => [
                        'attr' => [
                            'class' => 'block max-w-[625px] w-full bg-gray-50 border border-gray-100 py-3 pl-6 pr-3 rounded-lg'
                        ],
                        'row_attr' => [
                            'class' => 'space-y-2'
                        ]
                    ],
                    'mapped' => false,
                    'required' => true,
                    'first_options' => [
                        'label' => 'Nouveau mot de passe'
                    ],
                    'second_options' => [
                        'label' => 'Confirmation du nouveau mot de passe'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
