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
     * @var int
     */
    private $id;

    /**
     * @var Category
     */
    private $category;

    /**
     * @var string
     */
    private $name;

    /**
     * @var Sentence[]
     */
    private $sentences;

    /**
     * @var int
     */
    private $point = 0;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var bool
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

    /**
     * @return int
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param int $position
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