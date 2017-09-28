<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Category;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\Sentence;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadLessonData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = $manager->getRepository('AppBundle:Category')
            ->findOneBy(['slug' => 'english-stories']);

        $this->loadLessons($category, $manager);

        $category = $manager->getRepository('AppBundle:Category')
            ->findOneBy(['slug' => 'callan-method']);

        $this->loadLessons($category, $manager);

        $manager->flush();
    }

    private function loadLessons(Category $category, ObjectManager $manager)
    {
        $totalLesson = 2000;
        $name = 'My sister puts on her hat and mittens';
        $nameLength = strlen($name);

        for ($position = 1; $position <= $totalLesson; $position++) {
            $lesson = new Lesson();
            $lesson->setName(substr($name, 0, rand(30, $nameLength)) . $position);
            $lesson->setCategory($category);
            $lesson->setPosition($position);
            $lesson->setIsActive((bool) rand(0,1));

            $sentences = [
                "First Snowfall",
                "Today is November 26th",
                "It snowed all day today.",
                "The snow is beautiful.",
                "The snow finally stopped.",
                "My sister and I are excited.",
                "My Mom doesn't like the snow.",
                "My Mom has to shovel the drive way.",
                "My sister and I get to play.",
                "I put on my hat and mittens.",
                "My Mom puts on my scarf.",
                "My Mom zippers my jacket.",
                "My sister puts on her hat and mittens.",
                "My Mom puts on her scarf.",
                "My Mom zippers her jacket.",
                "My sister and I go outside.",
                "We begin to make a snow man.",
                "My Mom starts to shovel the snow.",
                "My sister and I make snow angels.",
                "My sister and I throw snowballs.",
                "It starts to snow again.",
                "We go inside for hot chocolate."
            ];

            $points = 0;
            foreach ($sentences as $i => $content) {
                $point = str_word_count($content);
                $points += $point;
                $sentence = new Sentence();
                $sentence->setLesson($lesson);
                $sentence->setContent($content);
                $sentence->setPoint($point);
                $sentence->setPosition($i+1);
                $manager->persist($sentence);
            }

            $lesson->addPoint($points);
            $manager->persist($lesson);
        }

        $category->addTotalLessons($totalLesson);
        $manager->persist($category);
    }
}