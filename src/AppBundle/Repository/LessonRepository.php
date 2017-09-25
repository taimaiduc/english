<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
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
            ->andWhere('lesson.isActive = :isActive')
            ->setParameter('isActive', true);
    }

    public function findNextLesson(Lesson $lesson)
    {
        return $this->createQueryBuilder('lesson')
            ->andWhere('lesson.category = :category')
            ->setParameter('category', $lesson->getCategory())
            ->andWhere('lesson.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('lesson.position > :position')
            ->setParameter('position', $lesson->getPosition())
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function findPreviousLesson(Lesson $lesson)
    {
        return $this->createQueryBuilder('lesson')
            ->andWhere('lesson.category = :category')
            ->setParameter('category', $lesson->getCategory())
            ->andWhere('lesson.isActive = :isActive')
            ->setParameter('isActive', true)
            ->andWhere('lesson.position < :position')
            ->setParameter('position', $lesson->getPosition())
            ->orderBy('lesson.position', 'desc')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}