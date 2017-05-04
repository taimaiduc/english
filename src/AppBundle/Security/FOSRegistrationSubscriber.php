<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/23/17
 * Time: 10:29 AM
 */

namespace AppBundle\Security;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;

class FOSRegistrationSubscriber implements EventSubscriberInterface
{
    public function onRegistrationFailure(FormEvent $event)
    {
        // if it's not an ajax call, let the fos bundle handle its work
        if (!$event->getRequest()->isXmlHttpRequest()) {
            return null;
        }

        $form = $event->getForm();

        if (!$form->getErrors()) {
            $event->setResponse(new JsonResponse());
        } else {
            // There is some errors, prepare a failure response
            $errors = $this->getFormErrors($form);

            // Set the status to Bad Request in order to grab it in front (i.e $.ajax({ ...}).error(...))
            $response = new JsonResponse($errors, 400);
            $event->setResponse($response);
        }
    }

    private function getFormErrors(FormInterface $form)
    {
        $errors = [];

        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }


    public function onRegistrationSuccess(FormEvent $event)
    {
        if (!$event->getRequest()->isXmlHttpRequest()) {
            return;
        }

        $response = new JsonResponse("success");

        $event->setResponse($response);
    }

    public static function getSubscribedEvents()
    {
        return array(
            FOSUserEvents::REGISTRATION_FAILURE => 'onRegistrationFailure',
            FOSUserEvents::REGISTRATION_SUCCESS => 'onRegistrationSuccess'
        );
    }
}