<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\Admin\UserType;
use App\Repository\CompanyRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[
    Route('/admin/user'),
    IsGranted('ROLE_ADMIN'),
]
class UserController extends AbstractController
{
    #[Route('/', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(UserRepository $userRepository, CompanyRepository $companyRepository): Response
    {
        $user = new User();
        $newUserForm = $this->createForm(UserType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_user_new'),
            'company_repository' => $companyRepository
        ]);

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'newUserForm' => $newUserForm
        ]);
    }

    #[Route('/new', name: 'app_admin_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository, CompanyRepository $companyRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_user_new'),
            'company_repository' => $companyRepository
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/user/index.html.twig', [
            'users' => $userRepository->findAll(),
            'newUserForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(UserType::class, $user,
            [
                'method' => 'POST',
                'action' => $this->generateUrl('app_admin_user_new'),
                'company_repository' => $companyRepository
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return new JsonResponse([
            'html' => $this->renderView('components/admin/user/edit_modal.html.twig', ['id' => $user->getId(), 'editForm' => $form->createView()]),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
