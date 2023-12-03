<?php

namespace App\Twig\Components;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/table_item.html.twig')]
class TableItem
{
    public string $id;
    public string $fullName;
    public string $email;
    public bool $isValid;
    public array $roles;
}