<?php

namespace App\Form\Admin;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;

class CustomerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'bg-slate-50 border border-gray-200 rounded-lg w-full p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'bg-slate-50 border border-gray-200 rounded-lg w-full p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('email', EmailType::class,
                [
                    'attr' => [
                        'class' => 'w-full h-[50px] border border-gray-200 rounded-lg mt-2 bg-slate-50 p-3 pl-6',
                    ],
                    'label' => 'Adresse e-mail',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('fullAdress', TextType::class,
                [
                    'attr' => [
                        'class' => 'w-full h-[50px] border border-gray-200 rounded-lg mt-2 bg-slate-50 p-3 pl-6',
                    ],
                    'label' => 'Adresse',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ])
            ->add('phoneNumber', TelType::class,
                [
                    'attr' => [
                        'class' => 'w-full h-[50px] border border-gray-200 rounded-lg mt-2 bg-slate-50 p-3 pl-6',
                    ],
                    'label' => 'Numéro de téléphone',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ])
            ->add('isCompany', ChoiceType::class, [
                'attr' => [
                    'class' => 'w-full h-[50px] border border-gray-200 rounded-lg mt-2 bg-slate-50 p-3 pl-6',
                ],
                'label' => 'Statut',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ],
                'choices' => [
                    'Entreprise' => 1,
                    'Particulier' => 0,
                ],
                'placeholder' => 'Choisissez le statut du client'
            ])
            ->add('company', ChoiceType::class, [
                'attr' => [
                    'class' => 'w-full h-[50px] border border-gray-200 rounded-lg mt-2 bg-slate-50 p-3 pl-6',
                ],
                'label' => 'Entreprise',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ],
                'choices' => $options['company_repository']->findAll(),
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Choisir une entreprise',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
            'company_repository' => null
        ]);
    }
}
