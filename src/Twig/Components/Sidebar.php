<?php

namespace App\Twig\Components;

use App\Entity\User;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/authenticated/sidebar.html.twig')]
class Sidebar {

}