<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'block max-w-[625px] w-full bg-gray-50 border border-gray-100 py-3 pl-6 pr-3 rounded-lg'
                ],
                'label' => 'Adresse e-mail',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2'
                ]
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'block w-[300px] bg-gray-50 border border-gray-100 py-3 pl-6 pr-3 rounded-lg'
                ],
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'block w-[300px] bg-gray-50 border border-gray-100 py-3 pl-6 pr-3 rounded-lg'
                ],
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2'
                ]
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class
        ]);
    }
}
