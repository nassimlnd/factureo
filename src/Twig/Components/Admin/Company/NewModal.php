<?php

namespace App\Twig\Components\Admin\Company;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/company/new_modal.html.twig')]
class NewModal
{
    public ?FormView $newCompanyForm;
}