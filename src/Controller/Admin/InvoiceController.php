<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use App\Entity\InvoiceDetails;
use App\Form\Admin\Invoice1Type;
use App\Repository\CompanyRepository;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/invoice')]
class InvoiceController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/', name: 'app_admin_invoice_index', methods: ['GET'])]
    public function index(Request $request, InvoiceRepository $invoiceRepository): Response
    {
        $nbPages = $invoiceRepository->getAllNbPages(8);

        if ($request->query->get('page') != "") {
            $page = $request->get('page');
            $invoices = $invoiceRepository->findAllByPage($page, 8);
        } else {
            $invoices = $invoiceRepository->findAllByPage(1, 8);
        }

        return $this->render('admin/invoice/index.html.twig', [
            'invoices' => $invoices,
            'nbPages' => $nbPages,
        ]);
    }

    #[Route('/new', name: 'app_admin_invoice_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, CustomerRepository $customerRepository, CompanyRepository $companyRepository): Response
    {
        $invoice = new Invoice();
        $customers = $customerRepository->findAll();
        $companies = $companyRepository->findAll();

        if ($request->getMethod() == "POST") {
            $data = $request->getContent();
            $data = json_decode($data, true);
            $invoice = $data['invoice'];

            $customer = $customerRepository->findById($invoice['customer']['id']);
            $company = $companyRepository->findById($invoice['company']['id']);

            $invoiceObj = new Invoice();
            $invoiceObj->setCustomer($customer);
            $invoiceObj->setCreatedAt(date_create_immutable($invoice['createdAt']));
            $invoiceObj->setDueDate(date_create_immutable($invoice['dueDate']));
            $invoiceObj->setState(0);
            $invoiceObj->setType($invoice['type']);
            $invoiceObj->setCompany($company);
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

        return $this->render('admin/invoice/new.html.twig', [
            'customers' => $customers,
            'companies' => $companies,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_invoice_show', methods: ['GET'])]
    public function show(Invoice $invoice): Response
    {
        return $this->render('admin/invoice/show.html.twig', [
            'invoice' => $invoice,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_invoice_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(Invoice1Type::class, $invoice);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_invoice_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/invoice/edit.html.twig', [
            'invoice' => $invoice,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_invoice_delete', methods: ['POST'])]
    public function delete(Request $request, Invoice $invoice, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$invoice->getId(), $request->request->get('_token'))) {
            $entityManager->remove($invoice);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_invoice_index', [], Response::HTTP_SEE_OTHER);
    }
}
