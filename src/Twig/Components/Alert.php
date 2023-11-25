<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/alert.html.twig')]
class Alert
{
    public string $state;
    public string $content;
    public string $title;
}