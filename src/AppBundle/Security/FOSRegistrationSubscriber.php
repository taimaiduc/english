<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/23/17
 * Time: 10:29 AM
 */

namespace AppBundle\Authentication;


use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\FOSUserEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class FOSRegistrationSubscriber implements EventSubscriberInterface
{
    public function onRegistrationFailure(FormEvent $event)
    {
        $form = $event->getForm();

        if (count($validationErrors = $form->getErrors()) == 0) {
            return $event->setResponse(new JsonResponse(['success' => true]));
        }

        // There is some errors, prepare a failure response
        $body = [];

        // Add the errors in your response body
        foreach ($validationErrors as $error) {
            $body[] = [
                'property' => $error->getPropertyPath(), // The field
                'message'  => $error->getMessage() // The error message
            ];
        }

        // Set the status to Bad Request in order to grab it in front (i.e $.ajax({ ...}).error(...))
        $response = new JsonResponse($body, 400);
        $event->setResponse($response);
    }

    public function onRegistrationSuccess(FormEvent $event)
    {
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