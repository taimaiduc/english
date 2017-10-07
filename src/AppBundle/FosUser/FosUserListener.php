<?php

namespace AppBundle\FosUser;

use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\RouterInterface;

class FosUserListener implements EventSubscriberInterface
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var TwigEngine
     */
    private $twigEngine;

    public static function getSubscribedEvents()
    {
        return [
            FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordSuccess',
            FOSUserEvents::RESETTING_SEND_EMAIL_COMPLETED => 'onResettingSendEmailCompleted',
            FOSUserEvents::RESETTING_RESET_SUCCESS => 'onResettingResetSuccess'
        ];
    }

    public function __construct(RouterInterface $router,
                                TwigEngine $twigEngine)
    {
        $this->router = $router;
        $this->twigEngine = $twigEngine;
    }

    public function onChangePasswordSuccess(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse(
            $this->router->generate('user_progress')
        ));
    }

    public function onResettingSendEmailCompleted(GetResponseUserEvent $event)
    {
        $user = $event->getUser();
        $email = $user->getEmail();

        if ($email) {
            $event->setResponse(
                new RedirectResponse(
                    $this->router->generate('fos_user_resetting_check_email', [
                        'email' => $email
                    ])
                )
            );
        }
    }

    public function onResettingResetSuccess(FormEvent $event)
    {
        $event->setResponse(new RedirectResponse(
            $this->router->generate('user_progress')
        ));
    }
}