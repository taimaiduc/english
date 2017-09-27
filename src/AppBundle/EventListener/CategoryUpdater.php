<?php

namespace AppBundle\EventListener;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\Progress;
use AppBundle\Entity\Sentence;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CategoryUpdater
{
    public function postPersist(LifecycleEventArgs $args) {
        $entity = $args->getEntity();

        if ($entity instanceof Lesson) {
            $this->addTotalLesson($args, $entity);
        }

        if ($entity instanceof Progress) {
            $this->addTotalPoint($args, $entity);
        }

        if ($entity instanceof Sentence) {
            $this->setLessonPoint($args, $entity);
        }
    }

    private function setLessonPoint(LifecycleEventArgs $args, Sentence $entity) {
        $em = $args->getEntityManager();
        $lesson = $entity->getLesson();
        $lesson->setPoint($lesson->getPoint() + $entity->getPoint());

        $em->persist($lesson);
        $em->flush();
    }

    private function addTotalPoint(LifecycleEventArgs $args, Progress $entity) {
        $em = $args->getEntityManager();
        $user = $entity->getUser();

        $user->addPoint($entity->getLastestPoint());

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