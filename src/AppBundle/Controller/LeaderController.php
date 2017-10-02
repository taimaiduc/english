<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class LeaderController extends Controller
{
    /**
     * @Route("/leaders", name="leader_list")
     */
    public function indexAction()
    {
        $leaders = $this->getDoctrine()->getRepository('AppBundle:Progress')
            ->findLeadersToday();

        return $this->render('leader/index.html.twig', [
            'leaders' => $leaders
        ]);
    }
}