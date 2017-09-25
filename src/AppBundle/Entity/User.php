<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="SavedLesson", mappedBy="user")
     */
    private $savedLessons;

    /**
     * @ORM\OneToMany(targetEntity="DoneLesson", mappedBy="user")
     */
    private $doneLessons;

    /**
     * @ORM\OneToMany(targetEntity="Progress", mappedBy="user")
     */
    private $progress;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPoint = 0;

    /**
     * @ORM\Column(type="date")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        // -1 hour in case user signs up AFTER doing a lesson
        $this->updatedAt = new \DateTime('-1 hour');

        parent::__construct();
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
    public function addTotalPoint($point)
    {
        $this->totalPoint += $point;
    }

    /**
     * @return int
     */
    public function getTotalPoint()
    {
        return $this->totalPoint;
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