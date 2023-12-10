<?php

namespace App\Twig\Components\Admin\Estimate;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/estimate/delete_modal.html.twig')]
class DeleteModal
{
    public string $id;
}