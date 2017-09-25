<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SavedLessonRepository")
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
     * @ORM\OneToMany(targetEntity="SavedSentence", mappedBy="savedLesson")
     * @ORM\JoinColumn(nullable=false)
     */
    private $savedSentences;

    public function __construct(User $user, Lesson $lesson)
    {
        $this->user = $user;
        $this->lesson = $lesson;
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
     * @return SavedSentence[]
     */
    public function getSavedSentences()
    {
        return $this->savedSentences;
    }

    /**
     * @param mixed $savedSentences
     */
    public function setSavedSentences($savedSentences)
    {
        $this->savedSentences = $savedSentences;
    }
}