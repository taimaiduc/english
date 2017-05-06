<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/22/17
 * Time: 8:57 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends BaseController
{
    /**
     * @Route("/user/updateProgress", name="user_update_progress")
     * @Method("POST")
     */
    public function updateProgressAction(Request $request)
    {
        $this->checkXmlHttpRequest($request);

        if (!$user = $this->getUser()) {
            return new Response('', 403);
        }

        $em = $this->getDoctrine()->getManager();
        $lesson = $em->getRepository('AppBundle:Lesson')->find($request->request->get('lessonId'));

        $userCurrentProgress = $this->updateUserProgress($em, $user, $lesson->getTotalWords());
        $this->addFlash('success', 'Your progress has been updated successfully');

//        $leaderBoard = $em->getRepository('AppBundle:Ranking')->findAll()[0];
//        $this->updateLeaderBoard($userCurrentProgress, $leaderBoard);

        return new Response(null, 204);
    }

    /**
     * @Route("/user/saveLesson", name="user_save_lesson")
     */
    public function saveLesson(Request $request)
    {
        $this->checkXmlHttpRequest($request);

        if (!$user = $this->getUser()) {
            return new Response('', 403);
        }

        $lessonId = $request->request->get('lessonId');
        $doneSentences = $request->request->get('doneSentences');
    }

    /**
     * @Route("/user", name="user_show")
     */
    public function showAction(Request $request)
    {
        $user = $this->getUser();

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(null, $user ? 200 : 400);
        }

        return new Response('abc');
    }

    private function updateUserProgress(ObjectManager $em, User $user, $wordCount)
    {
        /*
        Assume that the date today is 2017-05-10:

        If a user has never done any lessons:
        $user = [
            'started_date' => null,
            'last_active' => null,
            'progress' => null
        ];

        If that user has done only one lesson on 2017-05-06:
        $user = [
            'stated_date' => '2017-05-06',
            'last_active' => '2017-05-06 17:43:32',
            'progress'  => [
                0 => 300 (300 words)
            ]
        ];

        If the user did some lessons later on:
        $user = [
            'stated_date' => '2017-05-06',
            'last_active' => '2017-05-10 10:21:02',
            'progress'  => [
                0 => 300 (He got 300 words on his started day, 2017-05-06),
                1 => 200 (He got 200 words on the day right after his started day, 2017-05-07),
                4 => 400 (He didn't do anything on 2017-05-08 and 2017-05-09, and got 400 words on 2017-05-10)
            ]
        ]
        */

        $timeNow = new \DateTime();

        // if the user hasn't started any lesson (all three values above were set to null as default values)
        // today = the number of days after the stated day.
        if (!$startedDate = $user->getStartedDate()) {
            $user->setStartedDate($timeNow);
            $progress = array();
            $today = 0;
        } else {
            $progress = $user->getProgress();
            $today = $timeNow->diff($startedDate)->d;
        }

        // if this is the first lesson that the user has done today
        if (!isset($progress[$today])) {
            $progress[$today] = $wordCount;
        } else {
            $progress[$today] += $this->validateWordCount($user->getLastActiveTime(), $timeNow, $wordCount);
        }

        $user->setProgress($progress);
        $user->setLastActiveTime($timeNow);

        $em->persist($user);
        $em->flush();

        return $user->getProgress();
    }

    private function updateLeaderBoard($userProgress)
    {

    }

    /**
     * @param \DateTime $lastActiveTime
     * @param \DateTime $timeNow
     * @param integer $wordCount
     * @return integer validatedWordCount;
     */
    private function validateWordCount(\DateTime $lastActiveTime, \DateTime $timeNow, $wordCount)
    {
        // if a user types faster than 1 word/second, he's probably cheating
        $seconds = $timeNow->diff($lastActiveTime)->s;

        return $wordCount/$seconds > 1 ? $wordCount : 0;
    }

    private function checkXmlHttpRequest(Request $request) {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }
    }
}