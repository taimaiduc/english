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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends BaseController
{
    /**
     * @Route("/user/updateProgress", name="user_update_progress")
     * @Method("POST")
     */
    public function updateProgressAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new Response("You are not logged in", 403);
        }

        /*
         * $dataExplained = array(
         *      'lessonId' => (int), // used to query lesson
         *      'username' => (string), // used to query User
         *      'correctAnswers' => array(
         *          (int) answerIndex => (int) wordCount,
         *          0 => 10, // means the first answer has 10 words
         *          1 => 4, // the second answer has 4 words, and so on.
         *      )
         * )
         */
        $data = $request->request->all();

        // user did not do anything
        if (!$userAnswers = $data['correctAnswers']) {
            return null;
        }

        $em = $this->getDoctrine()->getManager();

        $lesson = $em->getRepository('AppBundle:Lesson')->findOneBy(['id' => $data['lessonId']]);
        $answersInDatabase = $lesson->getSentences();

        $wordCount = $this->getValidWordCount($userAnswers, $answersInDatabase);

        /** @var User $user */
        $user = $this->getUser();

        $todayProgress = $this->updateUserProgress($em, $user, $wordCount);

        return new Response($todayProgress);
    }

    /**
     * @Route("/user/saveLesson", name="user_save_lesson")
     */
    public function saveLesson()
    {

    }

    /**
     * @Route("/user", name="user_show")
     */
    public function showAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            if ($user = $this->getUser()) {
                return new JsonResponse([
                    'username' => $user->getUsername()
                ]);
            }

            return new JsonResponse(null, 400);
        }

        return new Response('abc');
    }

    /**
     * @param $userAnswers
     * @param $ourAnswers
     * @return int
     */
    private function getValidWordCount($userAnswers, $ourAnswers)
    {
        $totalWordCount = 0;

        foreach ($userAnswers as $answerIndex => $wordCount) {
            if ($wordCount != count(explode(" ", $ourAnswers[$answerIndex]))) {
                continue;
            }
            $totalWordCount += $wordCount;
        }

        return $totalWordCount;
    }

    private function updateUserProgress(ObjectManager $em, User $user, $wordCount)
    {
        // if the user hasn't started any lesson, $currentProgress is set to null
        if (!$currentProgress = $user->getProgress()) {
            $currentProgress = array();
        }

        $lastActiveDate = new \DateTime();

        // if the user hasn't started any lesson, $startedDate is set to null
        if (!$startedDate = $user->getStartedDate()) {
            /** number of days after the first day user updates his progress */
            $dayOffset = 0;
            $user->setStartedDate($lastActiveDate);
        } else {
            $dayOffset = $lastActiveDate->diff($startedDate)->d;
        }

        if (!isset($currentProgress[$dayOffset])) {
            $currentProgress[$dayOffset] = 0;
        }
        $currentProgress[$dayOffset] += $wordCount;
        $user->setProgress($currentProgress);
        $user->setLastActiveDate($lastActiveDate);

        $em->persist($user);
        $em->flush();

        return $currentProgress[$dayOffset];
    }
}