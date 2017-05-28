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
     * $requestData = [
     *      lessonId = int
     *      isLessonDone = boolean
     *      doneSentences = array[sentence indexes] | null
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

        $requestData = json_decode($request->getContent(), true);

        /** @var int $lessonId */
        $lessonId = isset($requestData['lessonId']) ? $requestData['lessonId'] : 0;

        $em = $this->getDoctrine()->getManager();

        if (!$lesson = $em->getRepository('AppBundle:Lesson')->find($lessonId)) {
            throw new NotFoundHttpException();
        }

        /** @var boolean $isLessonDone */
        $isLessonDone = isset($requestData['isLessonDone']) ? $requestData['isLessonDone'] : false;

        /** @var array|null $doneSentences */
        $doneSentences = isset($requestData['doneSentences']) ? $requestData['doneSentences'] : null;

        $this->updateUserStats($em, $user, $lesson, $isLessonDone, $doneSentences);

//        $leaderBoard = $em->getRepository('AppBundle:Ranking')->findAll()[0];
//        $this->updateLeaderBoard($userCurrentProgress, $leaderBoard);

        $data = array(
            'todayProgress' => $user->getTodayProgress()
        );

        return new JsonResponse($data);
    }

    private function updateUserStats(ObjectManager $em, User $user, Lesson $lesson, $isLessonDone, $doneSentences = null)
    {
        $timeNow = new \DateTime();
        $firstActiveDate = $user->getFirstActiveDate();
        $today = $timeNow->diff($firstActiveDate)->format('%a');

        $progress = $user->getProgress();

        // if this is the first lesson that the user has done today
        // set to 0 to prevent undefined error
        if (!isset($progress[$today])) {
            $progress[$today] = 0;
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
                if ($doneSentences = array_diff($doneSentences, $savedLessons[$lessonId])) {
                    $savedLessons[$lessonId] = array_merge($savedLessons[$lessonId], $doneSentences);
                }
            }
        }
        else {
            if (!$isLessonDone) {
                $savedLessons[$lessonId] = $doneSentences;
            }
        }

        $totalWords = $this->validateWordCount(
            $user->getLastActiveTime(),
            $timeNow,
            $lesson->getTotalWords($doneSentences)
        );
        $progress[$today] += $totalWords;

        if ($isLessonDone) {
            $user->addDoneLesson($lessonId);
        } else {
            $user->setSavedLessons($savedLessons);
        }
        $user->setLastActiveTime($timeNow);
        $user->setProgress($progress);
        $em->persist($user);
        $em->flush();
    }

    /**
     * @param ObjectManager $em
     * @param User $user
     * @param $lessonId
     * @param $resetType 1 = reset lesson, 2 = delete lesson
     * @return array
     */
    private function resetLesson(ObjectManager $em, User $user, $lessonId, $resetType)
    {
        $savedLessons = $user->getSavedLessons();

        if ($resetType == 2) {
            if (isset($savedLessons[$lessonId])) {
                unset($savedLessons[$lessonId]);
            }
            $message = 'Xoa thanh cong';
        }
        else {
            if (isset($savedLessons[$lessonId])) {
                $savedLessons[$lessonId] = array();
            }
            $message = 'reset thanh cong';
        }

        $user->setSavedLessons($savedLessons);
        $em->persist($user);
        $em->flush();

        return [
            'message' => $message,
            'username' => $user->getUsername(),
            'todayProgress' => $user->getTodayProgress()
        ];
    }

    /**
     * @param \DateTime $lastActiveTime
     * @param \DateTime $timeNow
     * @param integer $wordCount
     * @return integer validatedWordCount;
     */
    private function validateWordCount(\DateTime $lastActiveTime = null, \DateTime $timeNow, $wordCount)
    {
        if (!$wordCount || !$lastActiveTime) {
            return 0;
        }

        // if a user types faster than 1 word/second, he's probably cheating
        $seconds = $timeNow->getTimestamp() - $lastActiveTime->getTimestamp();

        return $seconds/$wordCount > 1 ? $wordCount : 0;
    }
}