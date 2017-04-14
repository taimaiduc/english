<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends BaseController
{
    /**
     * @Route("/lessons", name="lesson_list")
     */
    public function listAction()
    {
        return $this->render('lesson/list.html.twig', ['a' => 'aaaa']);
    }
}