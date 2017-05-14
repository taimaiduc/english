<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

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

        return $this->render('lesson/list.html.twig', ['categories' => $categories]);
    }

    /**
     * @Route("/lessons/{categorySlug}/{position}", name="lessons_show")
     */
    public function showAction($categorySlug, $position)
    {
        /** @var Category $category */
        $category = $this->getDoctrine()->getRepository('AppBundle:Category')
            ->findOneBy(['slug' => $categorySlug]);

        /** @var Lesson $lesson */
        $lesson = $this->getDoctrine()->getRepository('AppBundle:Lesson')
            ->findOneBy(['category' => $category, 'position' => $position]);

        $data = array(
            'category' => $category,
            'lesson' => $lesson,
            'savedSentences' => array()
        );

        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $savedLessons = $user->getSavedLessons();
            foreach ($savedLessons as $lessonId => $savedSentences) {
                if (isset($savedLessons[$lesson->getId()])) {
                    $data['savedSentences'] = $savedSentences;
                }
            }
        }

        return $this->render('lesson/show.html.twig', $data);
    }
}