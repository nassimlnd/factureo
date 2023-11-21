<?php

namespace App\Twig\Components\Home;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/home/pricing_tile.html.twig')]
class PricingTile
{
    public string $name;
    public string $price;
    public string $description;

    public array $features;

    public bool $isPrincipal;
}