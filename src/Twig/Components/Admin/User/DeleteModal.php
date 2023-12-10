<?php

namespace App\Twig\Components\Admin\User;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/user/delete_modal.html.twig')]
class DeleteModal
{
    public int $id;
}