<?php

namespace AppBundle\Entity;

class Sentence
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var Lesson
     */
    private $lesson;

    /**
     * @var string
     */
    private $content;

    /**
     * @var string
     */
    protected $json_content;

    /**
     * @var int
     */
    private $point;

    /**
     * @var int
     */
    private $position;

    /**
     * @var bool
     */
    private $wasSaved;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * @param string $content
     */
    public function setContent($content)
    {
        $this->content = $content;
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
    public function setPoint($point)
    {
        $this->point = $point;
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
    public function getWasSaved()
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
}
