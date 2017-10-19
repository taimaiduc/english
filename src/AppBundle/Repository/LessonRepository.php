<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\DoneLesson;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\SavedLesson;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class LessonRepository extends EntityRepository
{
    public function findOneByCategorySlugAndPosition($categorySlug, $position)
    {
        return $this->createQueryBuilder('lesson')
            ->leftJoin('lesson.category', 'category')
            ->andWhere('category.slug = :slug')
            ->setParameter('slug', $categorySlug)
            ->andWhere('lesson.position = :position')
            ->setParameter('position', $position)
            ->getQuery()
            ->getOneOrNullResult();
    }

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

    /**
     * @param User $user
     * @return Lesson[]
     */
    public function findSavedLessons(User $user)
    {
        return $this->createQueryBuilder('l')
            ->innerJoin('AppBundle:SavedLesson', 'sl', 'WITH', 'l.id = sl.lesson AND sl.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }

    /**
     * Return an array
     * [
     *      lessonId => numberOfTimesDone,
     *      anotherId => anotherNumber
     * ]
     *
     * @param User $user
     * @return array
     */
    public function findDoneLessons(User $user)
    {
        $lessons = $this->createQueryBuilder('l')
            ->select('l.id')
            ->innerJoin('AppBundle:DoneLesson', 'dl', 'WITH', 'l.id = dl.lesson AND dl.user = :user')
            ->setParameter('user', $user)
            ->addSelect('dl.count')
            ->getQuery()
            ->execute();

        $result = [];

        foreach ($lessons as $key => $lesson) {
            $result[$lesson['id']] = $lesson['count'];
        }

        return $result;
    }
}