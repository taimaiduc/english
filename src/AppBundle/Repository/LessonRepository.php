<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    public function countAllLessonInOneCategory(Category $category)
    {
        return $this->createQueryBuilder('lesson')
            ->select('count(lesson.id)')
            ->andWhere('lesson.category = :category')
            ->setParameter('category', $category)
            ->getQuery()
            ->getSingleScalarResult();
    }
    public function findOneByCategoryQueryBuilder(Category $category)
    {
        return $this->createQueryBuilder('lesson')
            ->andWhere('lesson.category = :category')
            ->setParameter('category', $category)
        ;
    }
}