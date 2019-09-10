<?php

namespace App\Repository;

use App\Entity\Tasks;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;
use function Doctrine\ORM\QueryBuilder;
use function Symfony\Component\DependencyInjection\Loader\Configurator\expr;

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

    public function notEqualStatuses(array $names, int $user=null, array $priorities=[])
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('t')
            ->from('App:Tasks', 't')
            ->leftJoin('App:Statuses', 'st', 'WITH', 't.status=st.id');

        if (!empty($priorities)) {
            $qb->leftJoin('App:Priorities', 'p', 'WITH', 't.priority=p.id')
                ->where($qb->expr()->in('p.name', $priorities))
                ->andWhere($qb->expr()->notIn('st.name', $names));
        } else {
            $qb->where($qb->expr()->notIn('st.name', $names));
        }

        if (!is_null($user)) {
            $qb
                ->andWhere($qb->expr()->eq('t.author', $user));
        }

        $result = $qb->getQuery()->getResult();
        return $result;
    }

    public function equalStatuses(array $names)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb
            ->leftJoin('App:Statuses', 's', 'WITH', 't.status=s.id')
            ->where($qb->expr()->in('s.name', $names))
            ->getQuery()
            ->getResult()
        ;
    }
}
