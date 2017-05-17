<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/22/17
 * Time: 8:57 AM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends BaseController
{
    /**
     * @Route("/user", name="user_show")
     * @param Request $request
     * @return Response
     */
    public function showAction(Request $request)
    {
        $user = $this->getUser();

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(null, $user ? 200 : 400);
        }

        return new Response('abc');
    }

    /**
     * @Route("/user/updateProgress", name="user_update_progress")
     * @Method("POST")
     * @param Request $request
     * @return Response
     *
     * This function only accepts ajax requests from logged in users.
     * Cases may happen:
     * -----the user saves the lesson without doing any sentences-----
     * $_POST_Example = [
     *      'lessonId' => 1,
     *      'isLessonDone' => 0
     * ]
     *
     * -----the user saves the lesson-----
     * $_POST_Example = [
     *      'lessonId' => 1,
     *      'isLessonDone' => 0
     *      'doneSentences' => [0, 1, 2, 3, 4, 5, 6, 10 ,20]
     * ]
     * As the sentence indexes start from 1,
     * if a user wants to save a lesson but has not done any sentences,
     * $_POST_Example['doneSentences'] = [0]
     *
     * -----the user finished the lesson-----
     * $_POST_Example = [
     *      'lessonId' => 1
     *      'isLessonDone' => 1
     * ]
     *
     */
    public function updateProgressAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            throw new HttpException(403, 'Forbidden');
        }

        if (!$user = $this->getUser()) {
            throw new AccessDeniedException();
        }

        $em = $this->getDoctrine()->getManager();

        $lessonId = $request->request->get('lessonId');

        if (!$lesson = $em->getRepository('AppBundle:Lesson')->find($lessonId)) {
            throw new NotFoundHttpException();
        }

        /** @var String|'0'|'1' $isLessonDone */
        $isLessonDone = $request->request->get('isLessonDone');

        /** @var array|null $doneSentences */
        $doneSentences = $request->request->get('doneSentences');

        $updatedData = $this->updateUserProgress($em, $user, $lesson, $isLessonDone, $doneSentences);

//        $leaderBoard = $em->getRepository('AppBundle:Ranking')->findAll()[0];
//        $this->updateLeaderBoard($userCurrentProgress, $leaderBoard);

        return new JsonResponse($updatedData);
    }

    private function updateUserProgress(ObjectManager $em, User $user, Lesson $lesson, $isLessonDone, $doneSentences = null)
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

        If the user has done some lessons later on:
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
        // $today = the number of days after the started day.
        if (!$startedDate = $user->getFirstActiveDate()) {
            $user->setFirstActiveDate($timeNow);
            $progress = array();
            $today = 0;
        } else {
            $progress = $user->getProgress();
            $today = $timeNow->diff($startedDate)->format('%a');
        }

        // if this is the first lesson that the user has done today
        if (!isset($progress[$today])) {
            $progress[$today] = 0;
        }

        // if $doneSentences == []; Symfony sets it to null, we set it to [] again.
        if (!$isLessonDone && $doneSentences === null) {
            $doneSentences = [];
        }

        $lessonId = $lesson->getId();
        $savedLessons = $user->getSavedLessons();

        // if lesson had been saved
        if (isset($savedLessons[$lessonId])) {

            if ($isLessonDone) {
                $doneSentences = array_diff(array_keys($lesson->getSentences()), $savedLessons[$lessonId]);
                unset($savedLessons[$lessonId]);
            }
            else {
                if (count($doneSentences) > count($savedLessons[$lessonId])) {
                    $doneSentences = array_diff($doneSentences, $savedLessons[$lessonId]);
                    $savedLessons[$lessonId] = array_merge($savedLessons[$lessonId], $doneSentences);
                }
            }

        }
        else {

            if (!$isLessonDone) {
                $savedLessons[$lessonId] = $doneSentences;
            }

        }
        $totalWords = $lesson->getTotalWords($doneSentences);
        $totalWords = $this->validateWordCount($user->getLastActiveTime(), $timeNow, $totalWords);
        $progress[$today] += $totalWords;

        $user->setLastActiveTime($timeNow);
        $user->setSavedLessons($savedLessons);
        $user->setProgress($progress);
        $em->persist($user);
        $em->flush();

        $message = $isLessonDone ? 'ban da hoan thanh bai nay' : 'luu bai thanh cong';

        if (count($progress) <= 1) {
            $highestWordCountADay = 300;
        } else {
            $highestWordCountADay = max($progress);
        }
        $todayProgressPercentage = $progress[$today]/$highestWordCountADay;

        return [
            'message' => $message,
            'username' => $user->getUsername(),
            'todayProgress' => $progress[$today],
            'todayProgressPercentage' => $todayProgressPercentage
        ];
    }

    /**
     * @param \DateTime $lastActiveTime
     * @param \DateTime $timeNow
     * @param integer $wordCount
     * @return integer validatedWordCount;
     */
    private function validateWordCount(\DateTime $lastActiveTime, \DateTime $timeNow, $wordCount)
    {
        if (!$wordCount) {
            return 0;
        }

        // if a user types faster than 1 word/second, he's probably cheating
        $seconds = $timeNow->getTimestamp() - $lastActiveTime->getTimestamp();

        return $seconds/$wordCount > 1 ? $wordCount : 0;
    }
}