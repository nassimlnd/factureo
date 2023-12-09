<?php

namespace App\Twig\Components\Admin\Dashboard;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/dashboard/card.html.twig')]
class Card
{
    public string $emoji;
    public string $title;
    public string $stat;
    public string $link;

}