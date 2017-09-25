<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class SavedLessonRepository extends EntityRepository
{
    public function wasLessonSaved(User $user, Lesson $lesson)
    {
        if ($this->findOneBy(['user' => $user, 'lesson' => $lesson])) {
            return true;
        }

        return false;
    }
}