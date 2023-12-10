<?php

namespace App\Twig\Components\User\Invoice;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/user/invoice/invoice_item.html.twig')]
class InvoiceItem
{
    public int $id;
}