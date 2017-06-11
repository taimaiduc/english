<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 6/11/2017
 * Time: 9:52 PM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class LeaderController extends BaseController
{
    /**
     * @Route("/leaders", name="leader_list")
     */
    public function indexAction()
    {
        $leaders = $this->getDoctrine()->getRepository('AppBundle:User')
            ->findAllLeaderUsers();

        return $this->render('leader/index.html.twig', [
            'leaders' => $leaders
        ]);
    }
}