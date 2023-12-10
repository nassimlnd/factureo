<?php

namespace App\Controller\Admin;

use App\Entity\Estimate;
use App\Entity\EstimateDetails;
use App\Form\Estimate1Type;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use App\Repository\EstimateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/estimate')]
class EstimateController extends AbstractController
{
    #[Route('/', name: 'app_admin_estimate_index', methods: ['GET'])]
    public function index(EstimateRepository $estimateRepository): Response
    {
        return $this->render('admin/estimate/index.html.twig', [
            'estimates' => $estimateRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_estimate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, CompanyRepository $companyRepository): Response
    {
        $estimate = new Estimate();
        $customers = $customerRepository->findAll();
        $companies = $companyRepository->findAll();

        if ($request->getMethod() == "POST") {
            $data = $request->getContent();
            $data = json_decode($data, true);
            $estimate = $data['estimate'];

            $customer = $customerRepository->findById($estimate['customer']['id']);
            $company = $companyRepository->findById($estimate['company']['id']);

            $estimateObj = new Estimate();
            $estimateObj->setCustomer($customer);
            $estimateObj->setCreatedAt(date_create_immutable($estimate['createdAt']));
            $estimateObj->setDueDate(date_create_immutable($estimate['dueDate']));
            $estimateObj->setState(0);
            $estimateObj->setType($estimate['type']);
            $estimateObj->setCompany($company);
            $estimateObj->setTotalPrice($estimate['totalPrice']);

            foreach ($estimate['estimateItems'] as $estimateLine) {
                $estimateDetail = new EstimateDetails();
                $estimateDetail->setElement($estimateLine['name']);
                $estimateDetail->setQuantity($estimateLine['quantity']);
                $estimateDetail->setUnitPrice($estimateLine['unitPrice']);
                $estimateDetail->setEstimate($estimateObj);

                $entityManager->persist($estimateDetail);
                $entityManager->flush();

                $estimateObj->addEstimateDetail($estimateDetail);
            }

            $entityManager->persist($estimateObj);
            $entityManager->flush();

            return $this->json($estimate);
        }

        return $this->render('admin/estimate/new.html.twig', [
            'customers' => $customers,
            'companies' => $companies,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_estimate_show', methods: ['GET'])]
    public function show(Estimate $estimate): Response
    {
        return $this->render('admin/estimate/show.html.twig', [
            'estimate' => $estimate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estimate $estimate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Estimate1Type::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/estimate/edit.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, Estimate $estimate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($estimate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_estimate_index', [], Response::HTTP_SEE_OTHER);
    }
}
