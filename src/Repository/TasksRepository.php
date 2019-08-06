<?php

namespace App\Repository;

use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Tasks|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tasks|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tasks[]    findAll()
 * @method Tasks[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Tasks::class);
    }

    public function notEqualStatuses(array $names)
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t')
            ->from('App:Tasks', 't')
            ->leftJoin('App:Statuses', 'st', 'WITH', 't.status=st.id')
            ->where($qb->expr()->notIn('st.name', $names));
        $result = $qb->getQuery()->getResult();
        return $result;
    }
}
