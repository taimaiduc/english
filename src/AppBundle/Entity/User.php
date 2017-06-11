<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
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
     * @ORM\Column(type="datetime")
     */
    private $firstActiveDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $lastActiveTime;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $savedLessons;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $doneLessons;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $progress;

    /**
     * @ORM\Column(type="integer")
     */
    private $totalPoint = 0;

    public function __construct()
    {
        $this->firstActiveDate = new \DateTime();
        $this->lastActiveTime = new \DateTime('-1 hour');
        $this->savedLessons = '[]';
        $this->doneLessons = '[]';
        $this->progress = '[]';

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
    public function getDoneLessons()
    {
        if ($this->doneLessons === null) {
            $this->doneLessons = json_encode(array());
        }
        return json_decode($this->doneLessons, true);
    }

    /**
     * @param mixed $doneLessons
     */
    public function setDoneLessons($doneLessons)
    {
        $this->doneLessons = json_encode($doneLessons);
    }

    public function addDoneLesson($lessonId)
    {
        if (!$doneLessons = $this->getDoneLessons()) {
            $doneLessons = array();
        }

        if (isset($doneLessons[$lessonId])) {
            $doneLessons[$lessonId] = $doneLessons[$lessonId] + 1;
        } else {
            $doneLessons[$lessonId] = 1;
        }

        $this->setDoneLessons($doneLessons);
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
        if (count($lessons) > 5) {
            $lessons = array_slice($lessons, -2, null, true);
        }

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
            'point' => 0,
            'percentage' => 0
        );

        if (!$progress = $this->getProgress()) {
            return $todayProgress;
        }

        $dateToday = new \DateTime();

        if ($dateToday->diff($this->lastActiveTime)->format('%a') != 0) {
            return $todayProgress;
        }

        $highestPoint = max($progress);
        if ($highestPoint < 300) {
            $highestPoint = 300;
        }
        $todayPoint = $progress[max(array_keys($progress))];

        $todayProgress['point'] = $todayPoint;
        $todayProgress['percentage'] = round($todayPoint/$highestPoint*100);

        return $todayProgress;
    }

    public function getProgressWithDetails()
    {
        $detailedProgress = array();

        if (!$progress = $this->getProgress()) {
            return $detailedProgress;
        }

        $firstActiveDate = $this->getFirstActiveDate();
        $highestPoint = max($progress);

        foreach ($progress as $dayOffset => $point) {
            $detailedProgress[] = array(
                'date' => $firstActiveDate->add(new \DateInterval('P'.$dayOffset.'D'))->format('Y-m-d'),
                'point' => $point,
                'percentage' => round($point/$highestPoint*100)
            );
        }

        return $detailedProgress;
    }

    /**
     * @param $point
     */
    public function addTotalPoint($point)
    {
        $this->totalPoint += $point;
    }

    public function getTotalPoint()
    {
        return $this->totalPoint;
    }
}