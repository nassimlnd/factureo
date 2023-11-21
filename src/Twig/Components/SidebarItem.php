<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/authenticated/sidebar.html.twig')]
class SidebarItem {

    private string $icon;
    private string $title;
    private string $route;

}