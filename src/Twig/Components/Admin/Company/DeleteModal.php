<?php

namespace App\Twig\Components\Admin\Company;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/company/delete_modal.html.twig')]
class DeleteModal
{
    public int $id;
}