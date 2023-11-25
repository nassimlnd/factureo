<?php

namespace App\Controller\User;

use App\Entity\User;
use App\Form\UserInfoType;
use App\Form\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use function Symfony\Component\Translation\t;

class AccountController extends AbstractController
{
    #[
        Route('/account', name: 'app_user_account'),
        IsGranted('ROLE_USER')
    ]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $infoForm = $this->createForm(UserInfoType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_user_account_update')
        ]);
        $passwordForm = $this->createForm(UserPasswordType::class, $user, [
            'action' => $this->generateUrl('app_user_account_update'),
            'method' => 'POST'
        ]);


        return $this->render('user/account/index.html.twig', [
            'infoForm' => $infoForm,
            'passwordForm' => $passwordForm
        ]);
    }

    #[
        Route('/account/update', name: 'app_user_account_update', methods: ['POST']),
        IsGranted('ROLE_USER')
    ]
    public function update(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $infoForm = $this->createForm(UserInfoType::class, $user);
        $infoForm->handleRequest($request);

        $passwordForm = $this->createForm(UserPasswordType::class, $user);
        $passwordForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid())
        {
            $currentPassword = $passwordForm->get('currentPassword')->getData();
            if ($userPasswordHasher->isPasswordValid($user, $currentPassword))
            {
                $newPassword = $passwordForm->get('password')->getData();
                $passwordConfirm = $passwordForm->get('passwordConfirm')->getData();
                if ($newPassword === $passwordConfirm)
                {
                    $user->setPassword(
                        $userPasswordHasher->hashPassword(
                            $user,
                            $newPassword
                        )
                    );

                    $entityManager->persist($user);
                    $entityManager->flush();

                    $this->addFlash(
                        'success',
                        'Votre mot de passe a bien été modifié.'
                    );

                    return $this->redirectToRoute('app_user_account');
                }
            }
        }

        if ($infoForm->isSubmitted() && $infoForm->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'Votre compte a bien été mis à jour.'
            );

            return $this->redirectToRoute('app_user_account');
        }

        return $this->render('user/account/index.html.twig',
            [
                'passwordForm' => $passwordForm,
                'infoForm' => $infoForm
            ]
        );
    }

}
