<?php

namespace App\Repository;

use App\Entity\HistoryStatuses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method HistoryStatuses|null find($id, $lockMode = null, $lockVersion = null)
 * @method HistoryStatuses|null findOneBy(array $criteria, array $orderBy = null)
 * @method HistoryStatuses[]    findAll()
 * @method HistoryStatuses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HistoryStatusesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, HistoryStatuses::class);
    }

    // /**
    //  * @return HistoryStatuses[] Returns an array of HistoryStatuses objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?HistoryStatuses
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
