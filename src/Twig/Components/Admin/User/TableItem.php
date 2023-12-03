<?php

namespace App\Twig\Components\Admin\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/user/table_item.html.twig')]
class TableItem
{
    public string $id;
    public string $fullName;
    public string $email;
    public bool $isValid;
    public array $roles;
}