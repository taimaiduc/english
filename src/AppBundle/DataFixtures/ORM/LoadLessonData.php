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
        $categories = json_decode(file_get_contents(__DIR__.'/data.json'), true);

        $categoryPos = 1;
        foreach ($categories as $categoryData) {
            $category = new Category();
            $category->setName($categoryData['name']);
            $category->setSlug($categoryData['slug']);
            $category->setPosition($categoryPos);

            $lessonPos = 1;
            foreach ($categoryData['lessons'] as $lessonData) {
                $lesson = new Lesson();
                $lesson->setName($lessonData['name']);
                $lesson->setCategory($category);
                $lesson->setIsActive(true);
                $lesson->setPosition($lessonPos);

                $sentencePos = 1;
                $sentencesPoint = 0;
                foreach ($lessonData['sentences'] as $sentenceContent) {
                    $sentencePoint = str_word_count($sentenceContent);
                    $sentencesPoint += $sentencePoint;
                    $sentence = new Sentence();
                    $sentence->setLesson($lesson);
                    $sentenceContent = $this->toJsonContent($sentenceContent);
                    $sentence->setContent($sentenceContent['niceContent']);
                    $sentence->setJsonContent($sentenceContent['jsonContent']);
                    $sentence->setPoint($sentencePoint);
                    $sentence->setPosition($sentencePos);
                    $manager->persist($sentence);

                    $sentencePos++;
                }

                $lesson->addPoint($sentencesPoint);
                $lessonPos++;
                $manager->persist($lesson);
            }

            $category->addTotalLessons($lessonPos-1);
            $manager->persist($category);
            $categoryPos++;
        }

        $manager->flush();
    }

    private function toJsonContent($content)
    {
        $niceContent = explode(' ', $content);
        $content = strtolower($content);
        $content = preg_replace('/[^\w\s-_#|]*/', '', $content);
        $content = explode(' ', $content);

        foreach ($content as $key => $word) {
            if (strpos($word, '|')) {
                $word = str_replace('_', ' ', $word);
                $word = explode('|', $word);
                $content[$key] = $word;

                foreach ($word as $k => $w) {
                    if (strpos($w, ' ')) {
                        $w = explode(' ', $w);
                        $word[$k] = $w;
                        $content[$key][$k] = $w;
                    }
                }
            }
        }

        foreach ($niceContent as $key => $word) {
            if (strpos($word, '|')) {
                $word = str_replace('_', ' ', $word);
                $word = explode('|', $word);
                $niceContent[$key] = $word[0];
            }
        }

        return [
            'niceContent' => implode(' ', $niceContent),
            'jsonContent' => $content
        ];
    }
}
