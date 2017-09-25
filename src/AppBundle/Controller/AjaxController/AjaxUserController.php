<?php

namespace AppBundle\Controller\AjaxController;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\SavedLesson;
use AppBundle\Entity\SavedSentence;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
        if (!$lesson) {
            throw new \InvalidArgumentException();
        }

        if (!$sentenceIds = $request->request->get('sentences')) {
            throw new \InvalidArgumentException();
        }

        $user     = $this->getUser();
        $doctrine = $this->getDoctrine();
        $em       = $doctrine->getManager();

        $savedLesson = $doctrine->getRepository('AppBundle:SavedLesson')
            ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if (!$savedLesson) {
            $savedLesson = new SavedLesson($user, $lesson);
            $em->persist($savedLesson);
        }

        $savedSentences = $doctrine->getRepository('AppBundle:SavedSentence')
            ->findBy(['savedLesson' => $savedLesson, 'sentence' => $sentenceIds]);

        if ($savedSentences) {
            throw new \InvalidArgumentException('Sentences have already been saved!');
        }

        $sentences = $doctrine->getRepository('AppBundle:Sentence')
            ->findBy(['id' => $sentenceIds]);

        foreach ($sentences as $sentence) {
            $savedSentence = new SavedSentence($savedLesson, $sentence);
            $em->persist($savedSentence);
        }

        $em->flush();

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
        if (!$lesson) {
            throw new \InvalidArgumentException();
        }

        $user     = $this->getUser();
        $doctrine = $this->getDoctrine();
        $em       = $doctrine->getManager();
        $point    = $lesson->getPoint();

        $savedLesson = $doctrine->getRepository('AppBundle:SavedLesson')
            ->findOneBy(['user' => $user, 'lesson' => $lesson]);

        if ($savedLesson) {
            $sentences = [];
            foreach ($savedLesson->getSavedSentences() as $savedSentence) {
                $sentences[] = $savedSentence->getSentence();
            }
            $savedPoint = $doctrine->getRepository('AppBundle:Sentence')
                ->getTotalPoint($sentences);
            $point -= $savedPoint;
            $em->remove($savedLesson);
        }

        $em->flush();

        return new JsonResponse("<html><body></body></html>");
    }
}