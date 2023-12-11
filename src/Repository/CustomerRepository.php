<?php

namespace App\Repository;

use App\Entity\Customer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Customer>
 *
 * @method Customer|null find($id, $lockMode = null, $lockVersion = null)
 * @method Customer|null findOneBy(array $criteria, array $orderBy = null)
 * @method Customer[]    findAll()
 * @method Customer[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Customer::class);
    }


    public function findByFilter($recherche, $idOrderASC, $idOrderDESC,$isCompany): array
    {
        $query = $this->createQueryBuilder('c');

        if($recherche){
            $query->andWhere('c.firstName = :id')
                ->setParameter('id', $recherche);
        }
        else if($recherche){
            $query->andWhere('c.id = :id')
            ->setParameter('id', $recherche);
        }
        if($idOrderASC){
            $query->orderBy('c.id',$idOrderASC);
        }
        if($idOrderDESC){
            $query->orderBy('c.id',$idOrderDESC);
        }
        if($isCompany){
            $query->andWhere('c.isCompany = :isCompany')
                ->setParameter('isCompany', $isCompany);

        }

        return $query->getQuery()
            ->getResult();
    }

    /**
     * @throws NonUniqueResultException
     */
    public function findById($id) : Customer
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findByCompany($company) : array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.company = :company')
            ->setParameter('company', $company)
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

    public function getAllNbPages($limit)
    {
        $query = $this->createQueryBuilder('p')
            ->select('COUNT(p)')
            ->getQuery()
            ->getSingleScalarResult();

        return ceil($query / $limit);
    }

//    /**
//     * @return Customer[] Returns an array of Customer objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Customer
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
