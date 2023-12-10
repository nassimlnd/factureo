<?php

namespace App\Twig\Components\Admin\Transaction;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/transaction/delete_modal.html.twig')]
class DeleteModal
{
    public int $id;
}