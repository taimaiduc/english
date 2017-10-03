<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route("/register", name="user_register")
     */
    public function registerAction(Request $request)
    {
        $form = $this->createForm(RegistrationForm::class);

        $form->handleRequest($request);
        if ($form->isValid()) {
            /** @var User $user */
            $user = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Welcome '. $user->getUsername() . '!');

            return $this->redirect('/');
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }
}