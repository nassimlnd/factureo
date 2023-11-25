<?php

namespace App\Twig\Components\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/user/sidebar_item.html.twig')]
class SidebarItem {

    private string $icon;
    private string $title;
    private string $route;

}