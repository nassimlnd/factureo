<?php

namespace App\Twig\Components\Admin\Customer;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/customer/combobox.html.twig')]
class Combobox
{
    public array $data;
}