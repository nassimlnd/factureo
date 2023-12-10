<?php

namespace App\Twig\Components\Admin\Transaction;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/transaction/new_modal.html.twig')]
class NewModal
{
    public ?FormView $newTransactionForm;
}