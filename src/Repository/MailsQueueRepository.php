<?php

namespace App\Repository;

use App\Entity\MailsQueue;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MailsQueue|null find($id, $lockMode = null, $lockVersion = null)
 * @method MailsQueue|null findOneBy(array $criteria, array $orderBy = null)
 * @method MailsQueue[]    findAll()
 * @method MailsQueue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MailsQueueRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MailsQueue::class);
    }

    // /**
    //  * @return MailsQueue[] Returns an array of MailsQueue objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MailsQueue
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
