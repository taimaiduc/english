<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\Progress;
use Doctrine\ORM\Event\LifecycleEventArgs;

class CategoryUpdater
{
    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof Lesson) {
            $this->addTotalLesson($args, $entity);
        } elseif ($entity instanceof Progress) {
            $this->addTotalPoint($args, $entity);
        }
    }

    private function addTotalPoint(LifecycleEventArgs $args, Progress $entity) {
        $em = $args->getEntityManager();
        die('aa');
        $user = $entity->getUser();
        $user->addTotalPoint($entity->getLastestPoint());

        $em->persist($user);
        $em->flush();
    }

    private function addTotalLesson(LifecycleEventArgs $args, Lesson $entity) {
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