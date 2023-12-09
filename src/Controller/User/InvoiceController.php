<?php

namespace App\Controller\User;

use App\Entity\Invoice;
use App\Entity\InvoiceDetails;
use App\Form\User\InvoiceType;
use App\Form\User\NewInvoiceType;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
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
    public function new(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, LoggerInterface $logger): Response
    {
        $invoice = new Invoice();
        $customers = $customerRepository->findByCompany($this->getUser()->getCompany());

        if ($request->getMethod() == "POST") {
            $data = $request->getContent();
            $data = json_decode($data, true);
            $invoice = $data['invoice'];

            $customer = $customerRepository->findById($invoice['customer']['id']);

            $invoiceObj = new Invoice();
            $invoiceObj->setCustomer($customer);
            $invoiceObj->setCreatedAt(date_create_immutable($invoice['createdAt']));
            $invoiceObj->setDueDate(date_create_immutable($invoice['dueDate']));
            $invoiceObj->setState(0);
            $invoiceObj->setType($invoice['type']);
            $invoiceObj->setCompany($this->getUser()->getCompany());
            $invoiceObj->setTotalPrice($invoice['totalPrice']);

            foreach ($invoice['invoiceItems'] as $invoiceLine) {
                $invoiceDetail = new InvoiceDetails();
                $invoiceDetail->setElement($invoiceLine['name']);
                $invoiceDetail->setQuantity($invoiceLine['quantity']);
                $invoiceDetail->setUnitPrice($invoiceLine['unitPrice']);
                $invoiceDetail->setInvoice($invoiceObj);

                $entityManager->persist($invoiceDetail);
                $entityManager->flush();

                $invoiceObj->addDetail($invoiceDetail);
            }

            $entityManager->persist($invoiceObj);
            $entityManager->flush();

            return $this->json($invoice);
        }

        return $this->render('user/invoice/new.html.twig', [
            'customers' => $customers
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
