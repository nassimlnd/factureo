<?php

namespace App\Twig\Components\Admin\Estimate;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/estimate/table_item.html.twig')]
class TableItem
{
    public string $id;
    public string $customerName;
    public string $companyName;
    public string $type;
    public int $state;
    public array $tags;
}