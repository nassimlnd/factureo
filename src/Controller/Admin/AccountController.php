<?php

namespace App\Controller\Admin;

use App\Entity\Media;
use App\Form\MediaType;
use App\Form\User\UserInfoType;
use App\Form\User\UserPasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
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
        $user = $this->getUser();
        $media = new Media();

        $infoForm = $this->createForm(UserInfoType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_account_update')
        ]);

        $passwordForm = $this->createForm(UserPasswordType::class, $user, [
            'action' => $this->generateUrl('app_admin_account_update'),
            'method' => 'POST'
        ]);

        $mediaForm = $this->createForm(MediaType::class, $media, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_account_update')
        ]);


        return $this->render('admin/account/index.html.twig', [
            'infoForm' => $infoForm,
            'passwordForm' => $passwordForm,
            'mediaForm' => $mediaForm
        ]);
    }

    #[Route('/admin/account/update', name: 'app_admin_account_update', methods: ['POST'])]
    public function update(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();
        $media = new Media();

        $infoForm = $this->createForm(UserInfoType::class, $user);
        $infoForm->handleRequest($request);

        $passwordForm = $this->createForm(UserPasswordType::class, $user);
        $passwordForm->handleRequest($request);

        $mediaForm = $this->createForm(MediaType::class, $media);
        $mediaForm->handleRequest($request);

        if ($passwordForm->isSubmitted() && $passwordForm->isValid())
        {
            $currentPassword = $passwordForm->get('currentPassword')->getData();
            if ($userPasswordHasher->isPasswordValid($user, $currentPassword))
            {
                $newPassword = $passwordForm->get('password')->getViewData()['first'];
                $passwordConfirm = $passwordForm->get('password')->getViewData()['second'];
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
                        [
                            'message' => 'Votre mot de passe a bien été modifié.',
                            'title' => 'Mise à jour réussie'
                        ]
                    );

                    return $this->redirectToRoute('app_user_account');
                } else {
                    $this->addFlash(
                        'error',
                        [
                            'message' => 'Les mots de passe sont différents.',
                            'title' => 'Une erreur est survenue'
                        ]
                    );
                }
            } else {
                $this->addFlash(
                    'error',
                    [
                        'message' => 'Le mot de passe actuel est incorrect.',
                        'title' => 'Une erreur est survenue'
                    ]
                );

                return $this->redirectToRoute('app_admin_account');
            }
        }

        if ($infoForm->isSubmitted() && $infoForm->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash(
                'success',
                [
                    'message' => 'Votre compte a bien été mis à jour.',
                    'title' => 'Mise à jour réussie.'
                ]
            );

            return $this->redirectToRoute('app_admin_account');
        }

        if ($mediaForm->isSubmitted() && $mediaForm->isValid())
        {
            if ($user->getProfilePicture() !== null)
            {
                $pictureToDelete = $user->getProfilePicture();

                $user->setProfilePicture(null);
                $entityManager->persist($user);
                $entityManager->flush();

                $entityManager->remove($pictureToDelete);
                $entityManager->flush();
            }

            $media->setCreatedAt(new \DateTimeImmutable());
            $media->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($media);
            $entityManager->flush();

            $user->setProfilePicture($media);

            $entityManager->persist($user);
            $entityManager->flush();


            $this->addFlash(
                'success',
                [
                    'message' => 'Votre image a bien été ajoutée.',
                    'title' => 'Mise à jour réussie.'
                ]
            );

            return $this->redirectToRoute('app_admin_account');
        }

        return $this->render('user/account/index.html.twig',
            [
                'passwordForm' => $passwordForm,
                'infoForm' => $infoForm,
                'mediaForm' => $mediaForm
            ]
        );
    }

    #[Route('/admin/account/picture/delete', name: 'app_admin_account_picture_delete', methods: ['GET'])]
    public function pictureDelete(Request $request, EntityManagerInterface $entityManager)
    {
        $user = $this->getUser();
        if ($user->getProfilePicture() != null) {
            $pictureToDelete = $user->getProfilePicture();

            $user->setProfilePicture(null);
            $entityManager->persist($user);
            $entityManager->flush();

            $entityManager->remove($pictureToDelete);
            $entityManager->flush();

            $this->addFlash(
                'success',
                [
                    'message' => 'Votre image a bien été supprimée.',
                    'title' => 'Suppression réussie.'
                ]
            );

            return $this->redirectToRoute('app_admin_account');
        } else {
            $this->addFlash(
                'error',
                [
                    'message' => 'Vous n\'avez pas d\'image de profil.',
                    'title' => 'Une erreur est survenue.'
                ]
            );

            return $this->redirectToRoute('app_admin_account');
        }
    }
}
