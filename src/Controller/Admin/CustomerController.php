<?php

namespace App\Controller\Admin;

use App\Entity\Customer;
use App\Form\Admin\CustomerType;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_admin_customer_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository, CompanyRepository $companyRepository): Response
    {
        $customer = new Customer();
        $newCustomerForm = $this->createForm(CustomerType::class, $customer, [
            'action' => $this->generateUrl('app_admin_customer_new'),
            'method' => 'POST',
            'company_repository' => $companyRepository
        ]);

        return $this->render('admin/customer/index.html.twig', [
            'customers' => $customerRepository->findAll(),
            'newCustomerForm' => $newCustomerForm,
        ]);
    }

    #[Route('/new', name: 'app_admin_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer, [
            'action' => $this->generateUrl('app_admin_customer_new'),
            'method' => 'POST',
            'company_repository' => $companyRepository
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('admin/customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager, CompanyRepository $companyRepository): Response
    {
        $form = $this->createForm(CustomerType::class, $customer, [
            'action' => $this->generateUrl('app_admin_customer_edit', ['id' => $customer->getId()]),
            'method' => 'POST',
            'company_repository' => $companyRepository
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return new JsonResponse([
            'html' => $this->renderView('components/admin/customer/edit_modal.html.twig',
                ['id' => $customer->getId(), 'editForm' => $form->createView()]
            )
        ]);
    }

    #[Route('/{id}', name: 'app_admin_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
