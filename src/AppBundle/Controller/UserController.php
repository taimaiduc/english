<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use Doctrine\Common\Persistence\ObjectManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class UserController extends Controller
{
    /**
     * @Route("/user/progress", name="user_progress")
     */
    public function showAction()
    {
        /** @var User $user */
        if (!$user = $this->getUser()) {
            throw new AccessDeniedException();
        }

        $progressRepo = $this->getDoctrine()->getRepository('AppBundle:Progress');
        $highestPoint = $progressRepo
            ->findHighestPoint($user);

        $progressList = $progressRepo->findBy(
            ['user' => $user],
            ['date' => 'DESC']
        );

        foreach ($progressList as $progress) {
            $percentage = round($progress->getPoint() / $highestPoint * 100);
            $progress->setPercentage($percentage);
        }

        return $this->render('user/progress.html.twig', [
            'progressList' => $progressList
        ]);
    }
}