<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/user/customer/combobox.html.twig')]
class Combobox
{
    public array $data;
}