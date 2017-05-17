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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $firstActiveDate;

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
     * @return \DateTime
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
     * @return \DateTime
     */
    public function getFirstActiveDate()
    {
        return $this->firstActiveDate;
    }

    /**
     * @param mixed $firstActiveDate
     */
    public function setFirstActiveDate(\DateTime $firstActiveDate)
    {
        $this->firstActiveDate = $firstActiveDate;
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

    /**
     * @return array
     * $todayProgressExample = [
     *      'point' => 123
     *      'percentage' => 45
     * ]
     */
    public function getTodayProgress()
    {
        $todayProgress = array(
            'points' => 0,
            'percentage' => 0
        );
        $dateToday = new \DateTime();

        if ($dateToday->diff($this->lastActiveTime)->format('%a') != 0) {
            return $todayProgress;
        }

        $progress = $this->getProgress();

        $highestPoint = max($progress);
        $todayPoint = $progress[max(array_keys($progress))];

        $todayProgress['point'] = $todayPoint;
        $todayProgress['percentage'] = ($todayPoint/$highestPoint)*100;

        return $todayProgress;
    }
}