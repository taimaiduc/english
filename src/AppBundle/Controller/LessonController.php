<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
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

        foreach ($categories as $category) {
            $qb = $this->getDoctrine()->getRepository('AppBundle:Lesson')
                ->findOneByCategoryQueryBuilder($category);
            $pagerfanta = $this->getPagerfanta($qb, 30);
            $category->setPager($pagerfanta);
            $category->setLessons((array) $pagerfanta->getCurrentPageResults());
        }

        $data = [
            'categories' => $categories,
        ];

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
        $lessonRepo = $this->getDoctrine()->getRepository('AppBundle:Lesson');
        $lesson = $lessonRepo->findOneBy(['category' => $category, 'position' => $position]);

        if (!$lesson) {
            throw new NotFoundHttpException();
        }

        $lesson->setNextLesson($lessonRepo->findNextLesson($lesson));
        $lesson->setPreviousLesson($lessonRepo->findPreviousLesson($lesson));

        $data = [
            'lesson' => $lesson
        ];

        return $this->render('lesson/show.html.twig', $data);
    }
}