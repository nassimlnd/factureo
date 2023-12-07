<?php

namespace App\Controller\Admin;

use App\Entity\Company;
use App\Form\Admin\CompanyType;
use App\Repository\CompanyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/company')]
class CompanyController extends AbstractController
{
    #[Route('/', name: 'app_admin_company_index', methods: ['GET'])]
    public function index(CompanyRepository $companyRepository): Response
    {
        $company = new Company();
        $newCompanyForm = $this->createForm(CompanyType::class, $company, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_company_new')
        ]);

        return $this->render('admin/company/index.html.twig', [
            'companies' => $companyRepository->findAll(),
            'newCompanyForm' => $newCompanyForm
        ]);
    }

    #[Route('/new', name: 'app_admin_company_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $company = new Company();
        $form = $this->createForm(CompanyType::class, $company, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_company_new')
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($company);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_company_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/company/new.html.twig', [
            'company' => $company,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_company_show', methods: ['GET'])]
    public function show(Company $company): Response
    {
        return $this->render('admin/company/show.html.twig', [
            'company' => $company,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_company_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CompanyType::class, $company, [
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_company_index', [], Response::HTTP_SEE_OTHER);
        }


        return new JsonResponse([
            'html'=> $this->renderView('components/admin/company/edit_modal.html.twig',
                ['id' => $company->getId(), 'editForm' => $form->createView()]
            )
        ]);
    }

    #[Route('/{id}', name: 'app_admin_company_delete', methods: ['POST'])]
    public function delete(Request $request, Company $company, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$company->getId(), $request->request->get('_token'))) {
            $entityManager->remove($company);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_company_index', [], Response::HTTP_SEE_OTHER);
    }
}
