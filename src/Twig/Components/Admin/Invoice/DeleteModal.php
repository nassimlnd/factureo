<?php

namespace App\Twig\Components\Admin\Invoice;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/invoice/delete_modal.html.twig')]
class DeleteModal
{
    public string $id;
}