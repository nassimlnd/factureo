<?php

namespace App\Twig\Components\Admin\Message;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/message/table_item.html.twig')]
class TableItem
{
    public string $id;
    public string $fullName;
    public string $subject;
    public string $email;
    public string $state;
    public string $type;
}