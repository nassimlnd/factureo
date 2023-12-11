<?php

namespace App\Form;

use App\Entity\Company;
use App\Entity\Customer;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class,
            [
                    'attr' => [
                        'class' => ' w-48 h-12 py-2 px-2 pl-6 items-center gap-2 border border-gray-300 rounded bg-gray-50',
                    ],
                    'label' => 'Prénom',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('lastName',TextType::class,
                [
                    'attr' => [
                        'class' => ' w-48 h-12 py-2 px-2 pl-6 items-center gap-2 border border-gray-300 rounded bg-gray-50',
                    ],
                    'label' => 'Nom',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]

            ])
            ->add('email',EmailType::class,
            [
                'attr' => [
                    'class' => ' h-12 py-2 px-2 pl-6 items-center gap-2 self-stretch border border-gray-300 rounded bg-gray-50',
                ],
                'label' => 'Email',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('fullAdress',TextType::class,
            [
                'attr' => [
                    'class' => ' h-12 py-2 px-2 pl-6 items-center gap-2 self-stretch border border-gray-300 rounded bg-gray-50',
                ],
                'label' => 'Adresse',
                'row_attr' => [
                    'class' => 'flex flex-col '
                ]

            ])
            ->add('phoneNumber',TelType::class,
            [
                'attr' => [
                    'class' => ' h-12 py-2 px-2 pl-6 items-center gap-2 self-stretch border border-gray-300 rounded bg-gray-50',
                ],
                'label' => 'Numéro de téléphone',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('isCompany', CheckboxType::class, [
                'label' => 'Le client est il une entreprise?  ',

            ])
            ->add('company',EntityType::class, [
                'class' => Company::class,
                'choice_label' => 'name',
                'label' => 'Si oui, laquelle?  ',
                'attr' => [
                    'class' => ' w-fit h-12 p-4 items-center gap-2 border border-gray-300 rounded-lg bg-gray-50',
                ]

            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
