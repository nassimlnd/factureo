<?php

namespace App\Repository;

use App\Entity\Invoice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Invoice>
 *
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }


    public function findByUser($company)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.company = :company')
            ->setParameter('company', $company)
            ->getQuery()
            ->getResult();

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

    public function findAllType()
    {
        return $this->createQueryBuilder('t')
            ->select('t.type')
            ->distinct()
            ->getQuery()
            ->getResult();
    }

    public function findByType($type)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.type =:type')
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }

    public function findByState($state)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.state =:state')
            ->setParameter('state', $state)
            ->getQuery()
            ->getResult();
    }

    public function findByPage($company, $page, $limit)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.company =:company')
            ->setParameter('company', $company)
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    public function findAllByPage($page, $limit)
    {
        return $this->createQueryBuilder('p')
            ->setFirstResult(($page * $limit) - $limit)
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function getAllNbPages($limit)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($query / $limit);
    }

//    /**
//     * @return Invoice[] Returns an array of Invoice objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Invoice
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
