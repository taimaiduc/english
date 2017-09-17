<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="done_lesson")
 */
class DoneLesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="doneLessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;

    /**
     * @ORM\Column(type="smallint")
     */
    private $count;

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @return Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * @param Lesson $lesson
     */
    public function setLesson(Lesson $lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * @return int
     */
    public function getCount()
    {
        return $this->count;
    }

    /**
     * @param int $count
     */
    public function setCount($count)
    {
        $this->count = $count;
    }
}