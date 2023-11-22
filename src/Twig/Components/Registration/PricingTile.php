<?php

namespace App\Twig\Components\Registration;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/registration/pricing_tile.html.twig')]
class PricingTile
{
    public string $name;
    public string $price;
    public string $description;
    public array $features;
    public bool $isPrincipal;
    public string $route;
}