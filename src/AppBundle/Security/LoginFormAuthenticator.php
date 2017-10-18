<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Translation\TranslatorInterface;

class LoginFormAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    public function __construct(FormFactoryInterface $formFactory,
                                EntityManager $em,
                                RouterInterface $router,
                                UserPasswordEncoder $passwordEncoder,
                                TranslatorInterface $translator)
    {
        $this->formFactory = $formFactory;
        $this->em = $em;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->translator = $translator;
    }

    public function getCredentials(Request $request)
    {
        if ($request->getPathInfo() != "/login" || !$request->isMethod('POST')) {
            return null;
        }

        $form = $this->formFactory->create(LoginForm::class);
        $form->submit($request->request->all());

        $data = $form->getData();

        $request->getSession()->set(Security::LAST_USERNAME, $data['_username']);

        return $data;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $username = $credentials['_username'];

        return $this->em->getRepository('AppBundle:User')
            ->findOneByUsernameOrEmail($username);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        $password = $credentials['_password'];

        if ($this->passwordEncoder->isPasswordValid($user, $password)) {
            return true;
        }

        return false;
    }

    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse($this->router->generate('app.security.login'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');
        $flashBag->set('error', [
            $this->translator->trans('authentication.failed')
        ]);

        return new RedirectResponse($this->router->generate('app.security.login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $route = $request->getSession()->get('last_visited_lesson_url');

        if (!$route) {
            $route = $this->router->generate('app.homepage');
        } else {
            $request->getSession()->remove('last_visited_lesson_url');
        }

        return new RedirectResponse($route);
    }

    public function supportsRememberMe()
    {
        return true;
    }
}