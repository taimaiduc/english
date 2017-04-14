<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class LessonController extends BaseController
{
    /**
     * @Route("/lessons")
     */
    public function listAction()
    {
        return $this->render('AppBundle:lesson:list.html.twig', ['a' => 'aaaa']);
    }
}