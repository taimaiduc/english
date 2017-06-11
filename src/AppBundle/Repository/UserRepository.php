<?php

namespace AppBundle\Repository;


use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findAllLeaderUsers()
    {
        return $this->createQueryBuilder('user')
            ->select('user.username,user.totalPoint')
            ->andWhere('user.totalPoint > :totalPoint')
            ->setParameter('totalPoint', 0)
            ->orderBy('user.totalPoint', 'desc')
            ->getQuery()
            ->getResult();
    }
}