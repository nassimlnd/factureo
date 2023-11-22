<?php

namespace App\Form;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RegistrationCompanyFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Nom de l\'entreprise'
                    ],
                    'label' => false
                ]
            )
            ->add('email', EmailType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Adresse e-mail'
                    ],
                    'label' => false
                ]
            )
            ->add('fullAdress', TextType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Adresse'
                    ],
                    'label' => false
                ]
            )
            ->add('phoneNumber', TelType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Numéro de téléphone'
                    ],
                    'label' => false
                ]
            )
            ->add('businessSector', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Secteur d\'activité'
                    ],
                    'label' => false,
                    'choices' => [
                        'Type 1' => 'secteur1'
                    ]
                ]
            )
            ->add('legalStatus', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Statut juridique'
                    ],
                    'label' => false,
                    'choices' => [
                        'Choice 1' => 'status1'
                    ]
                ]
            )
            ->add('activityType', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Type d\'activité'
                    ],
                    'label' => false,
                    'choices' => [
                        'Activity 1' => 'activité1'
                    ]
                ]
            )
            ->add('country', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Pays'
                    ],
                    'label' => false,
                    'choices' => [
                        'Country 1' => 'country1'
                    ]
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Company::class,
        ]);
    }
}
