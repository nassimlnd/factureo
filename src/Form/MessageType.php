<?php

namespace App\Form;

use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('subject', TextType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Objet'
                    ],
                    'label' => false
                ]
            )
            ->add('content', TextareaType::class,
                [
                    'attr' => [
                        'rows' => '8',
                        'class' => 'w-full min-h-[100px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6 resize-none',
                        'placeholder' => 'Votre message...'
                    ],
                    'label' => false
                ]
            )
            ->add('fullName', TextType::class,
                [
                    'attr' => [
                        'class' => 'w-[405px] h-[50px] border border-gray-200 rounded-lg mt-2 bg-gray-50 p-3 pl-6',
                        'placeholder' => 'Nom complet'
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'message_contact_token'
        ]);
    }
}
