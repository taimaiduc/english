<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LeaderController extends Controller
{
    public function indexAction()
    {
        $leaders = $this->getDoctrine()->getRepository('AppBundle:Progress')
            ->findLeadersToday();

        return $this->render('AppBundle::leader/index.html.twig', [
            'leaders' => $leaders
        ]);
    }
}