<?php

namespace AppBundle\Controller\AjaxController;

use AppBundle\Entity\DoneLesson;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\Progress;
use AppBundle\Entity\SavedLesson;
use AppBundle\Entity\SavedSentence;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class AjaxUserController extends Controller
{
    /**
     * @Route("/ajax/user/saveLesson/{lesson}", name="ajax_user_save_lesson")
     *
     * @param Lesson $lesson
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxSaveLessonAction(Lesson $lesson, Request $request)
    {
        /** @var User $user */
        if (!$user = $this->getUser()) {
            throw new AccessDeniedException();
        }

        if (!$lesson) {
            throw new \InvalidArgumentException();
        }

        if (!$sentenceIds = $request->request->get('sentences')) {
            throw new \InvalidArgumentException();
        }

        $doctrine = $this->getDoctrine();
        $em       = $doctrine->getManager();

        $savedLesson = $doctrine->getRepository('AppBundle:SavedLesson')
            ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if ($savedLesson) {
            $savedSentences = $doctrine->getRepository('AppBundle:SavedSentence')
                ->findBy(['savedLesson' => $savedLesson, 'sentence' => $sentenceIds]);

            if ($savedSentences) {
                throw new \InvalidArgumentException('Sentences have already been saved!');
            }
        } else {
            $savedLesson = new SavedLesson($user, $lesson);

        }

        $point = 0;
        $sentences = $doctrine->getRepository('AppBundle:Sentence')
            ->findBy(['id' => $sentenceIds]);

        foreach ($sentences as $sentence) {
            $point += $sentence->getPoint();
            $savedSentence = new SavedSentence($savedLesson, $sentence);
            $em->persist($savedSentence);
        }

        $savedLesson->addPoint($point);
        $em->persist($savedLesson);

        $this->updateUserProgress($em, $user, $point);

        return new JsonResponse();
    }

    /**
     * @Route("/ajax/user/completeLesson/{lesson}", name="ajax_user_complete_lesson")
     *
     * @param Lesson $lesson
     * @return JsonResponse
     */
    public function ajaxCompleteLessonAction(Lesson $lesson)
    {
        /** @var User $user */
        if (!$user = $this->getUser()) {
            throw new AccessDeniedException();
        }

        if (!$lesson) {
            throw new \InvalidArgumentException();
        }

        $doctrine   = $this->getDoctrine();
        $em         = $doctrine->getManager();
        $point      = $lesson->getPoint();
        $userLesson = ['user' => $user, 'lesson' => $lesson];

        $savedLesson = $doctrine->getRepository('AppBundle:SavedLesson')
            ->findOneBy($userLesson);

        if ($savedLesson) {
            $point -= $savedLesson->getPoint();
            $em->remove($savedLesson);
        }

        $doneLesson = $doctrine->getRepository('AppBundle:DoneLesson')
            ->findOneBy($userLesson);

        if (!$doneLesson) {
            $doneLesson = new DoneLesson();
            $doneLesson->setUser($user);
            $doneLesson->setLesson($lesson);
        }

        $doneLesson->addCount();
        $em->persist($doneLesson);

        $this->updateUserProgress($em, $user, $point);

        return new JsonResponse("<html><body></body></html>");
    }

    private function updateUserProgress(ObjectManager $em, User $user, $point)
    {
        $now = new \DateTime();
        $progress = $this->getDoctrine()->getRepository('AppBundle:Progress')
            ->findOneBy(['user' => $user, 'date' => $now]);

        if ($progress) {
            $progress->addPoint($point);
        } else {
            $progress = new Progress();
            $progress->setUser($user);
            $progress->setPoint($point);
            $progress->setDate($now);
        }

        $user->addPoint($point);
        $em->persist($user);
        $em->persist($progress);

        $em->flush();
    }
}