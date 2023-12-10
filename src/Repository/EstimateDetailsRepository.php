<?php

namespace App\Repository;

use App\Entity\EstimateDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EstimateDetails>
 *
 * @method EstimateDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method EstimateDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method EstimateDetails[]    findAll()
 * @method EstimateDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EstimateDetailsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EstimateDetails::class);
    }

//    /**
//     * @return EstimateDetails[] Returns an array of EstimateDetails objects
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

//    public function findOneBySomeField($value): ?EstimateDetails
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
