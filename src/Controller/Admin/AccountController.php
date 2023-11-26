<?php

namespace App\Controller\Admin;

use App\Form\User\UserInfoType;
use App\Form\User\UserPasswordType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AccountController extends AbstractController
{
    #[
        Route('/admin/account', name: 'app_admin_account'),
        IsGranted('ROLE_ADMIN')
    ]
    public function index(): Response
    {
        $infoForm = $this->createForm(UserInfoType::class);
        $passwordForm = $this->createForm(UserPasswordType::class);

        return $this->render('admin/account/index.html.twig', [
            'infoForm' => $infoForm,
            'passwordForm' => $passwordForm
        ]);
    }
}
