<?php

namespace AppBundle\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @param string $username
     * @return null|User
     */
    public function findOneByUsernameOrEmail($username)
    {
        return $this->createQueryBuilder('user')
            ->where('user.email = :email')
            ->setParameter('email', $username)
            ->orWhere('user.username = :username')
            ->setParameter('username', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}