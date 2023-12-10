<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TransactionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('paymentMethod', ChoiceType::class, [
                    'attr' => [
                        'class' => 'rounded-lg border p-2.5 pl-6 bg-slate-100 border border-gray-200 ml-2'
                    ],
                    'label' => 'Méthode de paiement',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ],
                    'choices' => [
                        'Chèque' => 'Chèque',
                        'Virement' => 'Virement',
                        'Espèces' => 'Espèces',
                        'Paiement en plusieurs fois' => 'Paiement en plusieurs fois',
                        'Carte bancaire' => 'Carte bancaire',
                        'Prélèvement automatique' => 'Prélèvement automatique',
                        'PayPal' => 'PayPal',
                        'Autre' => 'Autre',
                    ],
                ]
            )
            ->add('amount', NumberType::class, [
                'attr' => [
                    'class' => 'rounded-lg border p-2.5 pl-6 bg-slate-100 border border-gray-200 ml-2'
                ],
                'label' => 'Montant',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ]])
            ->add('state', ChoiceType::class, [
                    'attr' => [
                        'class' => 'bg-slate-100 border border-gray-200 rounded-lg w-[424px] p-2.5 pl-6 h-[50px]'
                    ],
                    'label' => 'État',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ],
                    'placeholder' => 'Choisir un état',
                    'choices' => [
                        'En cours' => '0',
                        'Payé' => '1'
                    ],
                ]
            )
            ->add('paymentDate', DateType::class, [
                'attr' => [
                    'class' => 'rounded-lg border p-2.5 pl-6 bg-slate-100 border border-gray-200 ml-2'
                ],
                'label' => 'Date de paiement',
                'row_attr' => [
                    'class' => 'flex flex-col space-y-2 w-full'
                ],
                'widget' => 'single_text',
            ])
            ->add('invoice', ChoiceType::class, [
                    'attr' => [
                        'class' => 'rounded-lg border p-2.5 pl-6 bg-slate-100 border border-gray-200 ml-2'
                    ],
                    'label' => 'Facture',
                    'row_attr' => [
                        'class' => 'flex flex-col space-y-2 w-full'
                    ],
                    'choices' => $options['invoice_repository']->findAll(),
                    'choice_label' => function ($value) {
                        return $value;
                    },
                    'choice_value' => 'id',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
            'invoice_repository' => null,
            'customer_repository' => null,
        ]);
    }
}
