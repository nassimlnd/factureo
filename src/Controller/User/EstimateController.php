<?php

namespace App\Controller\User;

use App\Entity\Estimate;
use App\Form\EstimateType;
use App\Repository\CustomerRepository;
use App\Repository\EstimateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/estimate')]
class EstimateController extends AbstractController
{
    #[Route('/', name: 'app_user_estimate_index', methods: ['GET'])]
    public function index(EstimateRepository $estimateRepository,Request $request,CustomerRepository $customerRepository): Response
    {
        $user = $this->getUser();
        $type= $request->query->get('type');
        $customer = $request->query->get('customer');
        $state = $request->query->get('state');
        if ($request->get('critere') != "") {
            if ($request->get('critere') === "customer")
            {
                $estimateSort = $estimateRepository->findByCustomer($user->getCompany(), $customer);
                return $this->render('user/estimate/index.html.twig', [
                    'estimates' => $estimateSort,
                    'customers' => $customerRepository->findAll(),
                    'types' => $estimateRepository->findAllType(),
                ]);
            }
            if($request->get('critere') === "type")
            {
                $estimateSort = $estimateRepository->findByType($type);
                return $this->render('user/estimate/index.html.twig',[
                    'estimates' => $estimateSort,
                    'customers' => $customerRepository->findAll(),
                    'types' => $estimateRepository->findAllType(),
                ]);
            }
            if($request->get('critere') == "state")
            {
                $estimateSort = $estimateRepository->findByState($state);
                return $this->render('user/estimate/index.html.twig', [
                    'estimates' => $estimateSort,
                    'customers' => $customerRepository->findAll(),
                    'types' => $estimateRepository->findAllType(),
                ]);
            }
        }
        return $this->render('user/estimate/index.html.twig', [
            'estimates' => $estimateRepository->findByUser($user->getCompany()),
            'customers' => $customerRepository->findAll(),
            'types' => $estimateRepository->findAllType(),
        ]);
    }

    #[Route('/new', name: 'app_user_estimate_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $estimate = new Estimate();
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($estimate);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/estimate/new.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_estimate_show', methods: ['GET'])]
    public function show(Estimate $estimate): Response
    {
        return $this->render('user/estimate/show.html.twig', [
            'estimate' => $estimate,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_estimate_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Estimate $estimate, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EstimateType::class, $estimate);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_estimate_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/estimate/edit.html.twig', [
            'estimate' => $estimate,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_estimate_delete', methods: ['POST'])]
    public function delete(Request $request, Estimate $estimate, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$estimate->getId(), $request->request->get('_token'))) {
            $entityManager->remove($estimate);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_estimate_index', [], Response::HTTP_SEE_OTHER);
    }
}
