<?php

namespace AppBundle\Doctrine;

use AppBundle\Entity\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;

class HashPasswordListener implements EventSubscriber
{
    /**
     * @var UserPasswordEncoder
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoder $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        $this->encodePassword($args);
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        $this->encodePassword($args);
    }

    private function encodePassword(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if (!$entity instanceof User) {
            return;
        }

        $encoded = $this->passwordEncoder->encodePassword($entity, $entity->getPlainPassword());
        $entity->setPassword($encoded);
    }

    public function getSubscribedEvents()
    {
        return ['prePersist', 'preUpdate'];
    }
}