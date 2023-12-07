<?php

namespace App\Repository;

use App\Entity\Estimate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Estimate>
 *
 * @method Estimate|null find($id, $lockMode = null, $lockVersion = null)
 * @method Estimate|null findOneBy(array $criteria, array $orderBy = null)
 * @method Estimate[]    findAll()
 * @method Estimate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Estimate::class);
    }
    public function findAllType()
    {
        return $this->createQueryBuilder('t')
            ->select('t.type')
            ->distinct()
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
    public function findByCustomer($company, $customer)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.company =:company AND i.customer = :customer')
            ->setParameter('company', $company)
            ->setParameter('customer', $customer)
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
//    /**
//     * @return Estimate[] Returns an array of Estimate objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Estimate
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
