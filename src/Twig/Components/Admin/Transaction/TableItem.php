<?php

namespace App\Twig\Components\Admin\Transaction;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/transaction/table_item.html.twig')]
class TableItem
{
    public int $id;
    public string $companyName;
    public string $customerName;
    public string $paymentMethod;
    public int $amount;
    public int $state;
    public string $date;

}