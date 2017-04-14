<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/14/17
 * Time: 10:50 PM
 */

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
}