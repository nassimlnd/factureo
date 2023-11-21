<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('lastName', TextType::class, array(
                'attr' => array(
                    'class' => 'w-[195px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    'placeholder' => 'Nom'
                ),
                'label' => false
            ))
            ->add('firstName', TextType::class, array(
                'attr' => array(
                    'class' => 'w-[195px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    'placeholder' => 'Prénom'
                ),
                'label' => false
            ))
            ->add('email', EmailType::class, array(
                'attr' => array(
                    'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    'placeholder' => 'Adresse e-mail'
                ),
                'label' => false
            ))
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'attr' => [
                    'class' => 'shrink-0 mt-0.5 border-gray-200 rounded text-blue-600 focus:ring-blue-500 disabled:opacity-50 disabled:pointer-events-none dark:bg-gray-800 dark:border-gray-700 dark:checked:bg-blue-500 dark:checked:border-blue-500 dark:focus:ring-offset-gray-800'
                ],
                'row_attr' => [
                    'class' => 'flex items-center justify-between w-[405px]'
                ],
                'label' => 'J\'accepte les conditions générales d\'utilisation',
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                    'placeholder' => 'Mot de passe',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe.',
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'Votre mot de passe doit contenir au minimum {{ limit }} caractères.',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
