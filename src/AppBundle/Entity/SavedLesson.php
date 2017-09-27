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
            /** @var SavedSentence $savedSentence */
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