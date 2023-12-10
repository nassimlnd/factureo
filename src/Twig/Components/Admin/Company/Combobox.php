<?php

namespace App\Twig\Components\Admin\Company;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/company/combobox.html.twig')]
class Combobox
{
    public array $data;
}