<?php

namespace AppBundle\Security;

use AppBundle\Form\LoginForm;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
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
        return new RedirectResponse($this->router->generate('security_login'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        /** @var FlashBagInterface $flashBag */
        $flashBag = $request->getSession()->getBag('flashes');
        $flashBag->set('error', [
            $this->translator->trans('authentication.failed')
        ]);

        return new RedirectResponse($this->router->generate('security_login'));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $route = null;

        if ($referer = $referer = $request->request->get('_referer')) {
            $host = $request->getHost();
            $uri = $request->getRequestUri();
            $refererUri = parse_url($referer, PHP_URL_PATH);

            if (false !== strpos($referer, $host) && $refererUri != $uri) {
                $route = $referer;
            }
        }

        if (null === $route) {
            $route = $this->router->generate('lessons_list');
        }

        return new RedirectResponse($route);
    }

    public function supportsRememberMe()
    {
        return true;
    }
}