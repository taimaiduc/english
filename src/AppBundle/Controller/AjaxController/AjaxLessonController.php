<?php

namespace AppBundle\Controller\AjaxController;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Lesson;
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

        $category = $this->getDoctrine()->getRepository('Category.orm.yml')
            ->findOneBy(['slug' => $categorySlug]);

        if (!$category) {
            throw new NotFoundHttpException();
        }

        $page = $request->query->get('page', 1);

        $qb = $this->getDoctrine()->getRepository('AppBundle:Lesson')
            ->findOneByCategoryQueryBuilder($category);
        $pagerfanta = $this->getPagerfanta($qb, 30, $page);

        $lessons = (array) $pagerfanta->getCurrentPageResults();

        if ($user = $this->getUser()) {
            $lessonRepo = $this->getDoctrine()->getRepository('AppBundle:Lesson');
            $savedLessons = $lessonRepo->findSavedLessons($user);
            $doneLessons = $lessonRepo->findDoneLessons($user);

            foreach ($lessons as $lesson) {
                /** @var Lesson $lesson */
                if (in_array($lesson, $savedLessons)) {
                    $lesson->setWasSaved(true);
                }

                if (isset($doneLessons[$lesson->getId()])) {
                    $lesson->setNumberOfTimesDone($doneLessons[$lesson->getId()]);
                }
            }
        }

        return $this->render('AppBundle::lesson/_list.html.twig', [
            'page' => $page,
            'category' => $category,
            'lessons' => $lessons
        ]);
    }
}