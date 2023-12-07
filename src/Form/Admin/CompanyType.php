<?php

namespace App\Form\Admin;

use App\Entity\Company;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CompanyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Nom de l\'entreprise',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('email', EmailType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
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
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Adresse',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('phoneNumber', TelType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Numéro de téléphone',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('businessSector', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Secteur d\'activité',
                    'choices' => [
                        'Agroalimentaire' => 'Agroalimentaire',
                        'Banque/Assurance'=> 'Banque/Assurance',
                        'Bois/Carton/Papier/Imprimerie' => 'Bois/Carton/Papier/Imprimerie',
                        'BTP/Matériaux de construction' => 'BTP/Matériaux de construction',
                        'Chimie/Parachimie' => 'Chimie/Parachimie',
                        'Commerce/Négoce/Distribution' => 'Commerce/Négoce/Distribution',
                        'Edition/Communication/Multimédia' => 'Edition/Communication/Multimédia',
                        'Electronique/Electricité' => 'Electronique/Electricité',
                        'Etudes et conseils' => 'Etudes et conseils',
                        'Industrie pharmaceutique' => 'Industrie pharmaceutique',
                        'Informatique/Télécoms' => 'Informatique/Télécoms',
                        'Machines et équipements/Automobile' => 'Machines et équipements/Automobile',
                        'Métallurgie/Travail du métal' => 'Métallurgie/Travail du métal',
                        'Plastique/Caoutchouc' => 'Plastique/Caoutchouc',
                        'Service aux entreprises' => 'Service aux entreprises',
                        'Textile/Habillement/Chaussure' => 'Textile/Habillement/Chaussure',
                        'Transport/Logistique' => 'Transport/Logistique'
                    ],
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('legalStatus', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Statut juridique',
                    'choices' => [
                        'Auto-entreprise' => 'AE',
                        'Entreprise individuelle' => 'EI',
                        'Entreprise unipersonnelle à responsabilité limitée' => 'EURL',
                        'Société à responsabilité limité' => 'SARL',
                        'Société anonyme' => 'SA',
                        'Société par actions simplifiées' => 'SAS',
                        'Société par actions simplifiées unipersonnelle' => 'SASU',
                        'Société en nom collectif' => 'SNC',
                        'Société coopérative de production' => 'SCOP',
                        'Société en commandite par actions' => 'SCA',
                        'Société en commandite simple' => 'SCS'
                    ],
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('activityType', ChoiceType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Type d\'activité',
                    'choices' => [
                        'Activity 1' => 'activité1'
                    ],
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ]
                ]
            )
            ->add('country', CountryType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    ],
                    'label' => 'Pays',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
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
