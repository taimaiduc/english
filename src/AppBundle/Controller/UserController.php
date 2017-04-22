<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/22/17
 * Time: 8:57 AM
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UserController extends BaseController
{
    /**
     * @Route("/user/updateProgress", name="user_update_progress")
     */
    public function updateProgressAction(Request $request)
    {
        if (!$this->get('security.authorization_checker')->isGranted('ROLE_USER')) {
            return new JsonResponse("You are not logged in", 403);
        }

        $user = $this->getUser();
        print_r($user); die;

        return new JsonResponse($_POST);
    }

    /**
     * @Route("user/saveLesson", name="user_save_lesson")
     */
    public function saveLesson()
    {

    }
}