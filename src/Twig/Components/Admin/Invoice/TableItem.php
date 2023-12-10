<?php

namespace App\Twig\Components\Admin\Invoice;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/invoice/table_item.html.twig')]
class TableItem
{
    public string $id;
    public string $customerName;
    public string $companyName;
    public string $type;
    public int $state;
    public array $tags;
}