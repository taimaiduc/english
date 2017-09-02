<?php

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Lesson;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadLessonData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = $manager->getRepository('AppBundle:Category')
            ->findOneBy(['slug' => 'english-stories']);

        $lesson = new Lesson();
        $lesson->setName('First snow fall');
        $lesson->setCategory($category);

        $sentences = [
            "First Snowfall",
            "Today is November 26th.",
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
        $lesson->setSentences($sentences);
        $lesson->setPosition(1);

        $manager->persist($lesson);

        for ($i = 2; $i < 11; $i++) {
            $lesson = new Lesson();
            $lesson->setName('First snow fall'.$i);
            $lesson->setCategory($category);
            $lesson->setSentences($sentences);
            $lesson->setPosition($i);

            $manager->persist($lesson);
        }

        $lesson = new Lesson();
        $lesson->setName('Lesson 1');
        $lesson->setCategory($category);

        $sentences = [
            "First Snowfall",
            "Today is November 26th.",
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
        $lesson->setSentences($sentences);
        $lesson->setPosition(1);

        $manager->flush();
    }
}