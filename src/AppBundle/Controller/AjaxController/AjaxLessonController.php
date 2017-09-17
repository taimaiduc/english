<?php

namespace AppBundle\Controller\AjaxController;

use AppBundle\Controller\BaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AjaxLessonController extends BaseController
{
    /**
     * @param Request $request
     * @return Response
     *
     * @Route("/ajax/lesson", name="ajax_lesson_list")
     */
    public function ajaxLessonListAction(Request $request)
    {
        $categorySlug = $request->query->get('categorySlug');

        $category = $this->getDoctrine()->getRepository('AppBundle:Category')
            ->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $page = $request->query->get('page', 1);

        $qb = $this->getDoctrine()->getRepository('AppBundle:Lesson')
            ->findOneByCategoryQueryBuilder($category);
        $pagerfanta = $this->getPagerfanta($qb, 30, $page);

        $lessons = [];
        foreach ($pagerfanta->getCurrentPageResults() as $lesson) {
            $lessons[] = $lesson;
        }

        return $this->render('lesson/_list.html.twig', [
            'page' => $page,
            'category' => $category,
            'lessons' => $lessons
        ]);
    }
}