<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/14/17
 * Time: 10:43 PM
 */

namespace AppBundle\EventListener;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CategoryUpdater
{
    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if (!$entity instanceof Lesson) {
            return;
        }

        $em = $args->getEntityManager();

        /** @var Category $category */
        $category = $entity->getCategory();

        $totalLessons = $em->getRepository('AppBundle:Lesson')
            ->countAllLessonInOneCategory($category);

        $category->setTotalLessons($totalLessons);

        $em->persist($category);
        $em->flush();
    }
}