<?php

namespace App\Twig\Components\Admin\Customer;

use Symfony\Component\Form\FormView;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(template: 'components/admin/customer/new_modal.html.twig')]
class NewModal
{
    public ?FormView $newCustomerForm;
}