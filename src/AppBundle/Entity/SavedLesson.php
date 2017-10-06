<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

class SavedLesson
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var User
     */
    private $user;

    /**
     * @var Lesson
     */
    private $lesson;

    /**
     * @var SavedSentence
     */
    private $savedSentences;

    /**
     * @ORM\Column(type="integer")
     */
    private $point = 0;

    public function __construct(User $user, Lesson $lesson)
    {
        $this->user = $user;
        $this->lesson = $lesson;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Lesson
     */
    public function getLesson()
    {
        return $this->lesson;
    }

    /**
     * @param mixed $lesson
     */
    public function setLesson($lesson)
    {
        $this->lesson = $lesson;
    }

    /**
     * @return Sentence[]
     */
    public function getSavedSentences()
    {
        $sentences = [];

        foreach ($this->savedSentences as $savedSentence) {
            $sentences[] = $savedSentence->getSentence();
        }

        return $sentences;
    }

    /**
     * @param mixed $savedSentences
     */
    public function setSavedSentences($savedSentences)
    {
        $this->savedSentences = $savedSentences;
    }

    /**
     * @return mixed
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param mixed $point
     */
    public function addPoint($point)
    {
        $this->point += $point;
    }
}
