<?php

namespace App\Form\Admin;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[424px] p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Adresse e-mail',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('roles', ChoiceType::class, [
                'mapped' => false,
                'attr' => [
                    'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[424px] p-2.5 pl-6 h-[50px]'
                ],
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ],
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN'
                ],
            ])
            ->add('password', PasswordType::class, [
                'attr' => [
                    'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[424px] p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Mot de passe',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('isVerified', CheckboxType::class, [
                'label' => 'Compte vérifié',
                'required' => false,
                'attr' => [
                    'class' => 'shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800'
                ],
                'row_attr' => [
                    'class' => 'flex items-center justify-between w-full'
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[200px] p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Prénom',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[200px] p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Nom',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]
            ])
            ->add('company', ChoiceType::class, [
                'attr' => [
                    'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[424px] p-2.5 pl-6 h-[50px]'
                ],
                'label' => 'Entreprise',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ],
                'choices' => $options['company_repository']->findAll(),
                'choice_label' => 'name',
                'choice_value' => 'id',
                'placeholder' => 'Choisir une entreprise',
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'company_repository' => null,
        ]);
    }
}
