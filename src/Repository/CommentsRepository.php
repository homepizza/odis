<?php

namespace App\Repository;

use App\Entity\Comments;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method Comments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Comments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Comments[]    findAll()
 * @method Comments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Comments::class);
    }

    /**
     * Комментаторы (участники задачи) без тех, которые указаны
     * (Например без автора и исполнителя)
     *
     * @param array $owners
     * @param int $task
     * @return mixed
     */
    public function members(int $task, array $owners=[])
    {
        $qb = $this->createQueryBuilder('c');
        $result = $qb->select('DISTINCT(c.user) AS id, u.fullname')
            ->leftJoin('App:User', 'u', 'WITH', 'c.user=u.id')
            ->where($qb->expr()->notIn('c.user', $owners))
            ->andWhere('c.task = :id')
            ->setParameter('id', $task)
            ->getQuery()
            ->getResult();
        return $result;
    }

    /**
     * Число комментариев к задаче
     *
     * @param int $task
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function messages(int $task)
    {
        return $this->createQueryBuilder('c')
            ->select('COUNT(c.id)')
            ->andWhere('c.task = :id')
            ->setParameter('id', $task)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
