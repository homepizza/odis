<?php

namespace App\Repository;

use App\Entity\Statuses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Statuses|null find($id, $lockMode = null, $lockVersion = null)
 * @method Statuses|null findOneBy(array $criteria, array $orderBy = null)
 * @method Statuses[]    findAll()
 * @method Statuses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StatusesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Statuses::class);
    }

    // /**
    //  * @return Statuses[] Returns an array of Statuses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Statuses
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
