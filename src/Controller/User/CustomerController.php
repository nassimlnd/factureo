<?php

namespace App\Controller\User;

use App\Entity\Customer;
use App\Form\CustomerType;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/customer')]
class CustomerController extends AbstractController
{
    #[Route('/', name: 'app_user_customer_index', methods: ['GET'])]
    public function index(CustomerRepository $customerRepository,Request $request,CompanyRepository $companyRepository): Response
    {
        $this->CustomerRepository = $customerRepository;
        $id = $request->query->get('recherche');
        $idOrderASC = $request->query->get('idOrderASC');
        $idOrderDESC = $request->query->get('idOrderDESC');
        $isCompany = $request->query->get('isCompany');
        $customers = $customerRepository->findByFilter($id,$idOrderASC,$idOrderDESC,$isCompany);

        $customer = new Customer();
        $newCustomerForm = $this->createForm(CustomerType::class, $customer, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_user_customer_new')
        ]);

        return $this->render('user/customer/index.html.twig', [
            'controller_name' => 'CustomerController',
            'id' => $id,
            'idOrderASC' => $idOrderASC,
            'idOrderDESC' => $idOrderDESC,
            'isCompany' => $isCompany,
            'customers' => $customers,
            'newCustomerForm' => $newCustomerForm,
            'companies' => $companyRepository->findAll()
        ]);
    }

    #[Route('/new', name: 'app_user_customer_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customer();
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($customer);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/customer/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_customer_show', methods: ['GET'])]
    public function show(Customer $customer): Response
    {
        return $this->render('user/customer/show.html.twig', [
            'customer' => $customer,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_customer_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomerType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_customer_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/customer/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_customer_delete', methods: ['POST'])]
    public function delete(Request $request, Customer $customer, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $entityManager->remove($customer);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_customer_index', [], Response::HTTP_SEE_OTHER);
    }
}
