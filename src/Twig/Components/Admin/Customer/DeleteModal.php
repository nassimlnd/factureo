<?php

namespace App\Twig\Components\Admin\Customer;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/customer/delete_modal.html.twig')]
class DeleteModal
{
    public int $id;
}