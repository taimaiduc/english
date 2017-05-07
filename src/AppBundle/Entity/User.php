<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
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
     * @ORM\Column(type="date", nullable=true)
     */
    private $startedDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastActiveTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $savedLessons;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $progress;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return mixed
     */
    public function getLastActiveTime()
    {
        return $this->lastActiveTime;
    }

    /**
     * @param mixed $lastActiveTime
     */
    public function setLastActiveTime(\DateTime $lastActiveTime)
    {
        $this->lastActiveTime = $lastActiveTime;
    }

    /**
     * @return mixed
     */
    public function getProgress()
    {
        return json_decode($this->progress, true);
    }

    /**
     * @param mixed $progress
     */
    public function setProgress($progress)
    {
        $this->progress = json_encode($progress);
    }

    /**
     * @return mixed
     */
    public function getStartedDate()
    {
        return $this->startedDate;
    }

    /**
     * @param mixed $startedDate
     */
    public function setStartedDate(\DateTime $startedDate)
    {
        $this->startedDate = $startedDate;
    }

    /**
     * @return mixed
     */
    public function getSavedLessons()
    {
        return json_decode($this->savedLessons, true);
    }

    /**
     * @param array $lessons
     */
    public function setSavedLessons(array $lessons)
    {
        $this->savedLessons = json_encode($lessons);
    }
}