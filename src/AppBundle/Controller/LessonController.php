<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class LessonController extends BaseController
{
    /**
     * @Route("/", name="lessons_list")
     */
    public function listAction()
    {
        /** @var Category[] $categories */
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')
            ->findAll();

        $data = array(
            'categories' => $categories
        );

        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $lessons = $this->getDoctrine()->getRepository('AppBundle:Lesson');

            $doneLessonArr = $user->getDoneLessons() ?: array();
            $doneLessons = $lessons->findBy(['id' => array_keys($doneLessonArr)]);
            foreach ($doneLessons as $doneLesson) {
                if (in_array($doneLesson->getId(), array_keys($doneLessonArr))) {
                    $doneLesson->setTimesHasDone($doneLessonArr[$doneLesson->getId()]);
                }
            }
            $savedLessonArr = $user->getSavedLessons() ?: array();
            $savedLessons = $lessons->findBy(['id' => array_keys($savedLessonArr)]);

            $data['doneLessonIds'] = array_keys($doneLessonArr);
            $data['doneLessons'] = $doneLessons;

            $data['savedLessonIds'] = array_keys($user->getSavedLessons());
            $data['savedLessons'] = $savedLessons;
        }

        return $this->render('lesson/list.html.twig', $data);
    }

    /**
     * @param $categorySlug
     * @param $position
     * @return Response
     * @Route("/lessons/{categorySlug}/{position}", name="lessons_show")
     */
    public function showAction($categorySlug, $position)
    {
        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')
            ->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        /** @var Lesson $lesson */
        $lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')
            ->findOneBy(['category' => $category, 'position' => $position]);

        if (!$lesson) {
            throw new NotFoundHttpException();
        }

        $data = array(
            'category' => $category,
            'lesson' => $lesson,
            'savedSentences' => array(),
            'progressPoint' => 0,
            'progressPercentage' => 0
        );

        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $savedLessons = $user->getSavedLessons() ? $user->getSavedLessons() : array();

            if (isset($savedLessons[$lesson->getId()])) {
                $data['savedSentences'] = $savedLessons[$lesson->getId()];
            }

            $progress = $user->getTodayProgress();
            $data['progressPoint'] = $progress['point'];
            $data['progressPercentage'] = $progress['percentage'];
        }

        return $this->render('lesson/show.html.twig', $data);
    }
}