<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

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
}