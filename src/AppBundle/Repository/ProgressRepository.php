<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class ProgressRepository extends EntityRepository
{
    public function findHighestPoint(User $user)
    {
        return $this->createQueryBuilder('progress')
            ->select('MAX(progress.point)')
            ->andWhere('progress.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    public function findLeadersToday()
    {
        $today = (new \DateTime())->format('Y-m-d');

        return $this->createQueryBuilder('p')
            ->select('p.point')
            ->andWhere('p.date = :today')
            ->setParameter('today', $today)
            ->leftJoin('p.user', 'u')
            ->andWhere('p.user = u')
            ->addSelect('u.username')
            ->orderBy('p.point', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->execute();
    }
}