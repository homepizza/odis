<?php

namespace App\Repository;

use App\Entity\DomainAreas;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DomainAreas|null find($id, $lockMode = null, $lockVersion = null)
 * @method DomainAreas|null findOneBy(array $criteria, array $orderBy = null)
 * @method DomainAreas[]    findAll()
 * @method DomainAreas[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DomainAreasRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DomainAreas::class);
    }

    // /**
    //  * @return DomainAreas[] Returns an array of DomainAreas objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DomainAreas
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
