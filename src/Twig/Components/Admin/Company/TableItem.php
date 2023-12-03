<?php

namespace App\Twig\Components\Admin\Company;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/company/table_item.html.twig')]
class TableItem
{
    public int $id;
    public string $name;
    public string $email;
    public string $legalStatus;
    public string $country;
}