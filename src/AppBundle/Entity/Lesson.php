<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/14/17
 * Time: 11:55 AM
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 * @ORM\Table(name="lesson")
 */
class Lesson
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Category", inversedBy="lessons")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $sentences;

    /**
     * @ORM\Column(type="integer")
     */
    private $position = 0;

    private $timesHasDone;

    /**
     * @return mixed
     */
    public function getTimesHasDone()
    {
        return $this->timesHasDone;
    }

    /**
     * @param mixed $timesHasDone
     */
    public function setTimesHasDone($timesHasDone)
    {
        $this->timesHasDone = $timesHasDone;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
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
    public function setCategory($category)
    {
        $this->category = $category;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getSentences()
    {
        return json_decode($this->sentences, true);
    }

    /**
     * @param array $sentences
     */
    public function setSentences(array $sentences)
    {
        $this->sentences = json_encode($sentences);
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * @param array|null $doneSentences
     * @return int $totalWords
     */
    public function getTotalWords($doneSentences = null)
    {
        $totalWords = 0;

        $lessonSentences = $this->getSentences();

        if (is_array($doneSentences)) {
            foreach ($doneSentences as $index) {
                $totalWords += count(explode(' ', $lessonSentences[$index]));
            }
        }
        else if ($doneSentences === null){
            foreach ($lessonSentences as $sentence) {
                $totalWords += count(explode(' ', $sentence));
            }
        }

        return $totalWords;
    }

    public function getNextLessonPosition()
    {
        if ($this->position >= $this->getCategory()->getTotalLessons()) {
            return null;
        }

        return $this->position + 1;
    }

    public function getPreviousLessonPosision()
    {
        if ($this->position <= 1) {
            return null;
        }

        return $this->position - 1;
    }
}