<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="saved_lesson")
 */
class SavedLesson
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="savedLessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;

    /**
     * @ORM\Column(type="string")
     */
    private $sentences;

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
     * @return Sentence
     */
    public function getSentences()
    {
        return $this->sentences;
    }

    /**
     * @param Sentence $sentences
     */
    public function setSentences(Sentence $sentences)
    {
        $this->sentences = $sentences;
    }
}