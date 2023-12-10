<?php

namespace App\Controller\Admin;

use App\Repository\CustomerRepository;
use App\Repository\InvoiceRepository;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    #[Route('/admin/dashboard', name: 'app_admin_dashboard')]
    public function index(CustomerRepository $customerRepository, InvoiceRepository $invoiceRepository, TransactionRepository $transactionRepository): Response
    {
        $nbCustomers = $customerRepository->count([]);
        $nbInvoices = $invoiceRepository->count([]);
        $totalAmountGenerated = $transactionRepository->getTotalAmountGenerated();

        if ($totalAmountGenerated === null) {
            $totalAmountGenerated = 0;
        }

        return $this->render('admin/dashboard/index.html.twig', [
            'nbCustomers' => $nbCustomers,
            'nbInvoices' => $nbInvoices,
            'totalAmountGenerated' => $totalAmountGenerated . ' €',
        ]);
    }
}
