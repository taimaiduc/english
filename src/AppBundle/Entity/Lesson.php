<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 * @ORM\Table(name="lesson")
 */
class Lesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="Sentence", mappedBy="lesson")
     */
    private $sentences;

    /**
     * @ORM\Column(type="integer")
     */
    private $point = 0;

    /**
     * @ORM\Column(type="smallint")
     */
    private $position = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isActive = true;

    /**
     * @var Lesson
     */
    private $previousLesson;

    /**
     * @var Lesson
     */
    private $nextLesson;

    /**
     * @var bool
     */
    private $wasSaved;

    /**
     * Number of times this lesson has been done
     *
     * @var int
     */
    private $numberOfTimesDone = 0;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @param Category $category
     */
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return Sentence[]
     */
    public function getSentences()
    {
        return $this->sentences;
    }

    /**
     * @param Sentence $sentence
     */
    public function addSentence(Sentence $sentence)
    {
        $this->sentences[] = $sentence;
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

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @return bool
     */
    public function getisActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return Lesson
     */
    public function getPreviousLesson()
    {
        return $this->previousLesson;
    }

    /**
     * @param Lesson $previousLesson
     */
    public function setPreviousLesson($previousLesson)
    {
        $this->previousLesson = $previousLesson;
    }

    /**
     * @return Lesson
     */
    public function getNextLesson()
    {
        return $this->nextLesson;
    }

    /**
     * @param Lesson $nextLesson
     */
    public function setNextLesson($nextLesson)
    {
        $this->nextLesson = $nextLesson;
    }

    /**
     * @return bool
     */
    public function isWasSaved()
    {
        return $this->wasSaved;
    }

    /**
     * @param bool $wasSaved
     */
    public function setWasSaved($wasSaved)
    {
        $this->wasSaved = $wasSaved;
    }

    /**
     * @return int
     */
    public function getNumberOfTimesDone()
    {
        return $this->numberOfTimesDone;
    }

    /**
     * @param int $numberOfTimesDone
     */
    public function setNumberOfTimesDone($numberOfTimesDone)
    {
        $this->numberOfTimesDone = $numberOfTimesDone;
    }
}