<?php

namespace AppBundle\Repository;

use AppBundle\Entity\SavedLesson;
use Doctrine\ORM\EntityRepository;

class SentenceRepository extends EntityRepository
{
    public function getTotalPoint(array $sentences)
    {
        return $this->createQueryBuilder('sentence')
            ->select('SUM (sentence.point) as point')
            ->andWhere('sentence.id in (:sentences)')
            ->setParameter('sentences', $sentences)
            ->getQuery()
            ->getSingleScalarResult();
    }
}