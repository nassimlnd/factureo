<?php

namespace App\Controller\User;

use App\Entity\Invoice;
use App\Form\User\InvoiceType;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/invoice')]
class InvoiceController extends AbstractController
{
    #[Route('/', name: 'app_user_invoice_index', methods: ['GET'])]
    public function index(InvoiceRepository $invoiceRepository, Request $request, CustomerRepository $customerRepository): Response
    {
        $user = $this->getUser();
        $type= $request->query->get('type');
        $customer = $request->query->get('customer');
        $state = $request->query->get('state');
        if ($request->get('critere') != "") {
            if ($request->get('critere') === "customer")
            {
                $invoiceSort = $invoiceRepository->findByCustomer($user->getCompany(), $customer);
                return $this->render('user/invoice/index.html.twig', [
                    'invoices' => $invoiceSort,
                    'customers' => $customerRepository->findAll(),
                    'types' => $invoiceRepository->findAllType(),
                ]);
            }
            if($request->get('critere') === "type")
            {
                $invoiceSort = $invoiceRepository->findByType($type);
                return $this->render('user/invoice/index.html.twig',[
                'invoices' => $invoiceSort,
                'customers' => $customerRepository->findAll(),
                'types' => $invoiceRepository->findAllType(),
            ]);
            }
            if($request->get('critere') == "state")
            {
                $invoiceSort = $invoiceRepository->findByState($state);
                return $this->render('user/invoice/index.html.twig', [
                    'invoices' => $invoiceSort,
                    'customers' => $customerRepository->findAll(),
                    'types' => $invoiceRepository->findAllType(),
                ]);
            }
        }

        return $this->render('user/invoice/index.html.twig', [
            'invoices' => $invoiceRepository->findByUser($user->getCompany()),
            'customers' => $customerRepository->findAll(),
            'types' => $invoiceRepository->findAllType(),
        ]);
    }

    #[Route('/new', name: 'app_user_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $invoice = new Invoice();
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($invoice);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/invoice/new.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('user/invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(InvoiceType::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
