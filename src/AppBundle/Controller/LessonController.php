<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends BaseController
{
    /**
     * @Route("/lessons", name="lessons_list")
     */
    public function listAction()
    {
        /** @var Category[] $categories */
        $categories = $this->getDoctrine()->getRepository('AppBundle:Category')
            ->findAll();

        foreach ($categories as $category) {
            $lessons = $this->getDoctrine()->getRepository('AppBundle:Lesson')
                ->findBy(['category' => $category]);

            $category->setLessons($lessons);
        }

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

        $data = [
            'category' => $category,
            'lesson' => $lesson
        ];

        return $this->render('lesson/show.html.twig', ['category' => $category, 'lesson' => $lesson]);
    }
}