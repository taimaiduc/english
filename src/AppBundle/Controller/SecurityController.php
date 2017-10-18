<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginForm;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction(Request $request)
    {
        if (null !== $this->getUser()) {
            return $this->redirectToRoute('app.lesson.list');
        }

        $authUtils = $this->get('security.authentication_utils');

        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        $form = $this->createForm(LoginForm::class, [
            '_username' => $lastUsername,
            '_referer' => $request->headers->get('referer')
        ]);

        return $this->render('AppBundle::security/login.html.twig', array(
            'form'  => $form->createView(),
            'error' => $error,
        ));
    }

    public function logoutAction()
    {
        throw new \Exception('This should not be reached');
    }

    public function checkEmailAction(Request $request)
    {
        $email = $request->query->get('email');

        if (!$email) {
            return $this->redirectToRoute('lessons_list');
        }

        return $this->render('@FOSUser/Resetting/check_email.html.twig', [
            'email' => $email
        ]);
    }
}