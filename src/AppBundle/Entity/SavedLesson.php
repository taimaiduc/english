<?php

namespace AppBundle\Entity;

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
     * @var SavedSentence[]
     */
    private $savedSentences;

    /**
     * @var int
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
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return User
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
     * @param Lesson $lesson
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
     * @param SavedSentence[] $savedSentences
     */
    public function setSavedSentences($savedSentences)
    {
        $this->savedSentences = $savedSentences;
    }

    /**
     * @return int
     */
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @param int $point
     */
    public function addPoint($point)
    {
        $this->point += $point;
    }
}
