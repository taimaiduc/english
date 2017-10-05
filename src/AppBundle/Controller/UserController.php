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
        $form = $this->createForm(RegistrationForm::class, [
            '_referer' => $request->headers->get('referer')
        ]);

        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();

            /** @var User $user */
            $user = new User();
            $user->setUsername($data['username']);
            $user->setEmail($data['email']);
            $user->setPlainPassword($data['plainPassword']);

            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Welcome '. $user->getUsername() . '!');
            $this->sendSuccessfulRegistrationEmail($data);

            return $this->get('security.authentication.guard_handler')
                ->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $this->get('app.security.login_form_authenticator'),
                    'main'
                );
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView()
        ]);
    }

    private function sendSuccessfulRegistrationEmail($data)
    {
        $mailer = $this->get('mailer');
        $translator = $this->get('translator');

        $message = (new \Swift_Message($translator->trans('email.registration.title')))
            ->setFrom('send@example.com')
            ->setTo('khoa-huy.nguyen@ekino.com')
            ->setBody(
                $this->renderView('email:registration.html.twig'),
                'text/html'
            );

        $mailer->send($message);

        return new Response('<html><body></body></html>');
    }
}