<?php

namespace App\Twig\Components\Admin\User;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/user/new_modal.html.twig')]
class NewModal
{
    public ?FormView $newUserForm;
}