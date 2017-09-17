<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="sentence")
 */
class Sentence
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Lesson", inversedBy="sentences")
     * @ORM\JoinColumn(nullable=false)
     */
    private $lesson;

    /**
     * @ORM\Column(type="string")
     */
    private $content;

    /**
     * @ORM\Column(type="smallint")
     */
    private $position;

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
}