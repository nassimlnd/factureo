<?php

namespace App\Twig\Components\Admin\Customer;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/customer/table_item.html.twig')]
class TableItem
{
    public int $id;
    public string $name;
    public string $email;
    public string $phone;
    public int $status;
}