<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Pagerfanta\Pagerfanta;

class Category
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $slug;

    /**
     * @var Lesson[]
     */
    private $lessons;

    /**
     * @var int
     */
    private $totalLessons = 0;

    /**
     * @var int
     */
    private $position = 0;

    /**
     * @var Pagerfanta
     */
    private $pager;

    public function __construct()
    {
        $this->lessons = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param string $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }

    /**
     * @return Lesson[]
     */
    public function getLessons()
    {
        return $this->lessons;
    }

    /**
     * @param Lesson[] $lessons
     */
    public function setLessons(array $lessons)
    {
        $this->lessons = $lessons;
    }

    /**
     * @return int
     */
    public function getTotalLessons()
    {
        return $this->totalLessons;
    }

    /**
     * @param int $totalLessons
     */
    public function setTotalLessons($totalLessons)
    {
        $this->totalLessons = $totalLessons;
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
     * @return Pagerfanta
     */
    public function getPager()
    {
        return $this->pager;
    }

    /**
     * @param Pagerfanta $pager
     */
    public function setPager($pager)
    {
        $this->pager = $pager;
    }

    public function __toString()
    {
        return $this->slug;
    }
}
