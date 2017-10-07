<?php

namespace AppBundle\Entity;

class SavedSentence
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var SavedLesson
     */
    private $savedLesson;

    /**
     * @var Sentence
     */
    private $sentence;

    public function __construct(SavedLesson $savedLesson, Sentence $sentence)
    {
        $this->savedLesson = $savedLesson;
        $this->sentence = $sentence;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getSavedLesson()
    {
        return $this->savedLesson;
    }

    /**
     * @param mixed $savedLesson
     */
    public function setSavedLesson($savedLesson)
    {
        $this->savedLesson = $savedLesson;
    }

    /**
     * @return Sentence
     */
    public function getSentence()
    {
        return $this->sentence;
    }

    /**
     * @param mixed $sentence
     */
    public function setSentence($sentence)
    {
        $this->sentence = $sentence;
    }
}
