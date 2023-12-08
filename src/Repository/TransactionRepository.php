<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Transaction>
 *
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }
    public function findByCustomer($company, $customer)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.company =:company AND i.customer = :customer')
            ->setParameter('company', $company)
            ->setParameter('customer', $customer)
            ->getQuery()
            ->getResult();
    }
    public function findByUser($company)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();

    }
    public function amountAsc(){
        return $this->createQueryBuilder('a')
            ->orderBy('a.amount', 'ASC')
            ->getQuery()
            ->getResult()
            ;

    }
    public function amountDesc(){
        return $this->createQueryBuilder('d')
            ->orderBy('d.amount', 'DESC')
            ->getQuery()
            ->getResult()
            ;

    }
    public function findAllAmount()
    {
        return $this->createQueryBuilder('t')
            ->select('t.amount')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    public function findAllPaymentDate()
    {
        return $this->createQueryBuilder('t')
            ->select('t.paymentDate')
            ->distinct()
            ->getQuery()
            ->getResult();
    }
    public function paymentDateAsc(){
        return $this->createQueryBuilder('a')
            ->orderBy('a.paymentDate', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }
    public function paymentDateDesc(){
        return $this->createQueryBuilder('d')
            ->orderBy('d.paymentDate', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }
//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Transaction
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
