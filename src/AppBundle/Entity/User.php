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
     * @ORM\Column(type="date", nullable=true)
     */
    private $lastActiveDate;

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
    public function getLastActiveDate()
    {
        return $this->lastActiveDate;
    }

    /**
     * @param mixed $lastActiveDate
     */
    public function setLastActiveDate($lastActiveDate)
    {
        $this->lastActiveDate = $lastActiveDate;
    }

    /**
     * @return mixed
     */
    public function getProgress()
    {
        return json_decode($this->progress);
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
    public function setStartedDate($startedDate)
    {
        $this->startedDate = $startedDate;
    }


}