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

        return $this->render('lesson/list.html.twig', ['categories' => $categories]);
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
            'savedSentences' => array()
        );

        /** @var User $user */
        $user = $this->getUser();
        if ($user) {
            $savedLessons = $user->getSavedLessons() ? $user->getSavedLessons() : array();

            if (isset($savedLessons[$lesson->getId()])) {
                $data['savedSentences'] = $savedLessons[$lesson->getId()];
            }
        }

        return $this->render('lesson/show.html.twig', $data);
    }
}