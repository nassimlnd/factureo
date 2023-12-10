<?php

namespace App\Controller\Admin;

use App\Entity\Transaction;
use App\Form\Admin\TransactionType;
use App\Repository\CompanyRepository;
use App\Repository\InvoiceRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_admin_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository, InvoiceRepository $invoiceRepository): Response
    {
        $transaction = new Transaction();

        $newTransactionForm = $this->createForm(TransactionType::class, $transaction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_transaction_new'),
            'invoice_repository' => $invoiceRepository,
        ]);

        return $this->render('admin/transaction/index.html.twig', [
            'transactions' => $transactionRepository->findAll(),
            'newTransactionForm' => $newTransactionForm->createView(),
        ]);
    }

    #[Route('/new', name: 'app_admin_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        $transaction = new Transaction();
        $newTransactionForm = $this->createForm(TransactionType::class, $transaction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_transaction_new'),
            'invoice_repository' => $invoiceRepository,
        ]);
        $newTransactionForm->handleRequest($request);

        if ($newTransactionForm->isSubmitted() && $newTransactionForm->isValid()) {
            $transaction->setCompany($transaction->getInvoice()->getCompany());
            $transaction->setCustomer($transaction->getInvoice()->getCustomer());

            $entityManager->persist($transaction);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/transaction/index.html.twig', [
            'transaction' => $transaction,
            'newTransactionForm' => $newTransactionForm,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('admin/transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        $editForm = $this->createForm(TransactionType::class, $transaction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_admin_transaction_edit', ['id' => $transaction->getId()]),
            'invoice_repository' => $invoiceRepository,
        ]);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return new JsonResponse([
            'html' => $this->renderView('components/admin/transaction/edit_modal.html.twig',
                ['id' => $transaction->getId(), 'editForm' => $editForm->createView()]),
        ]);
    }

    #[Route('/{id}', name: 'app_admin_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$transaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
