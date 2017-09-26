<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="saved_sentence")
 */
class SavedSentence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="SavedLesson", inversedBy="savedSentences")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $savedLesson;

    /**
     * @ORM\ManyToOne(targetEntity="Sentence")
     * @ORM\JoinColumn(nullable=false)
     */
    private $sentence;

    public function __construct(SavedLesson $savedLesson, Sentence $sentence)
    {
        $this->savedLesson = $savedLesson;
        $this->sentence = $sentence;
    }

    /**
     * @return mixed
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