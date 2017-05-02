<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
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

        return $this->render('lesson/show.html.twig', array('category' => $category, 'lesson' => $lesson));
    }
}