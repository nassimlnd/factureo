<?php

namespace App\Controller\User;

use App\Entity\Transaction;
use App\Form\TransactionType;
use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/transaction')]
class TransactionController extends AbstractController
{
    #[Route('/', name: 'app_user_transaction_index', methods: ['GET'])]
    public function index(TransactionRepository $transactionRepository, Request $request, CustomerRepository $customerRepository, InvoiceRepository $invoiceRepository): Response
    {
        $user = $this->getUser();
        $transaction = new Transaction();

        $customer = $request->query->get('customer');
        $state = $request->query->get('state');

        $newTransactionForm = $this->createForm(TransactionType::class, $transaction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_user_transaction_new'),
            'invoice_repository' => $invoiceRepository,
            'customer_repository' => $customerRepository
        ]);

        if ($request->get('critere') != "") {
            $transactionSort = [];
            if ($request->get('critere') === "customer") {
                $transactionSort = $transactionRepository->findByCustomer($user->getCompany(), $customer);
            }
            if ($request->get('critere') == "state") {
                $transactionSort = $transactionRepository->findByState($state);
            }
            if ($request->get('critere') == "paymentDate") {
                if ($request->get('paymentdate') == "Ascendant") {
                    $transactionSort = $transactionRepository->paymentDateAsc();
                } else if ($request->get('paymentdate') == "Descendant") {
                    $transactionSort = $transactionRepository->paymentDateDesc();
                }
            }
            if ($request->get('critere') == "amount") {
                if ($request->get('amount') == "Ascendant") {
                    $transactionSort = $transactionRepository->amountAsc();
                } else if ($request->get('amount') == "Descendant") {
                    $transactionSort = $transactionRepository->amountDesc();
                }
            }
            return $this->render('user/transaction/index.html.twig', [
                'transactions' => $transactionSort,
                'customers' => $customerRepository->findAll(),
                'newTransactionForm' => $newTransactionForm,
                'invoice_repository' => $invoiceRepository,
                'customer_repository' => $customerRepository
            ]);
        }

        return $this->render('user/transaction/index.html.twig', [
            'transactions' => $transactionRepository->findByUser($user->getCompany()),
            'customers' => $customerRepository->findAll(),
            'newTransactionForm' => $newTransactionForm,
            'invoice_repository' => $invoiceRepository,
            'customer_repository' => $customerRepository
        ]);
    }

    #[Route('/new', name: 'app_user_transaction_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, InvoiceRepository $invoiceRepository): Response
    {
        $transaction = new Transaction();
        $form = $this->createForm(TransactionType::class, $transaction, [
            'method' => 'POST',
            'action' => $this->generateUrl('app_user_transaction_new'),
            'invoice_repository' => $invoiceRepository,
        ]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $transaction->setCompany($this->getUser()->getCompany());
            $transaction->setCustomer($transaction->getInvoice()->getCustomer());
            if ($transaction->getAmount() < $transaction->getInvoice()->getTotalPrice()) {
                $entityManager->persist($transaction);
                $entityManager->flush();

                return $this->redirectToRoute('app_user_transaction_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('error', [
                    'message' => "Vous ne pouvez créer une transaction avec un montant supérieur à la facture",
                    'title' => "Une erreur est survenue"
                ]);
            }


            return $this->redirectToRoute('app_user_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/transaction/index.html.twig', [
            'transaction' => $transaction,
            'newTransactionForm' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_transaction_show', methods: ['GET'])]
    public function show(Transaction $transaction): Response
    {
        return $this->render('user/transaction/show.html.twig', [
            'transaction' => $transaction,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_user_transaction_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TransactionType::class, $transaction);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_user_transaction_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/transaction/edit.html.twig', [
            'transaction' => $transaction,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_user_transaction_delete', methods: ['POST'])]
    public function delete(Request $request, Transaction $transaction, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $transaction->getId(), $request->request->get('_token'))) {
            $entityManager->remove($transaction);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_user_transaction_index', [], Response::HTTP_SEE_OTHER);
    }
}
