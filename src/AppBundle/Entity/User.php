<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Security\Core\User\UserInterface;

class User extends BaseUser implements UserInterface
{
    /**
     * @var SavedLesson[]
     */
    private $savedLessons;

    /**
     * @var DoneLesson[]
     */
    private $doneLessons;

    /**
     * @var Progress[]
     */
    private $progress;

    /**
     * @var int
     */
    private $point = 0;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        parent::__construct();

        $now = new \DateTime();
        $this->createdAt = $now;
        $this->updatedAt = $now;
        $this->enabled = true;
    }

    /**
     * @return DoneLesson[]
     */
    public function getDoneLessons()
    {
        return $this->doneLessons;
    }

    /**
     * @param DoneLesson $doneLessons
     */
    public function setDoneLessons(DoneLesson $doneLessons)
    {
        $this->doneLessons = $doneLessons;
    }

    /**
     * @return Progress[]
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * @param Progress $progress
     */
    public function setProgress(Progress $progress)
    {
        $this->progress = $progress;
    }

    /**
     * @return SavedLesson[]
     */
    public function getSavedLessons()
    {
        return $this->savedLessons;
    }

    /**
     * @param mixed $savedLessons
     */
    public function setSavedLessons($savedLessons)
    {
        $this->savedLessons = $savedLessons;
    }

    public function addSavedLesson(SavedLesson $saveLesson)
    {
        $this->savedLessons[] = $saveLesson;
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
    public function getPoint()
    {
        return $this->point;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

}
