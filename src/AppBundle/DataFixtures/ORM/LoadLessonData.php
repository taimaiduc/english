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
                $lesson->setSlug($category->getSlug().'-'.$lessonPos);

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
                    $sentence->setAudioPath('/assets/mp3/' . $categoryData['slug'] . '/' . $lessonPos . '/' . $sentencePos . '.mp3');
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
        if (substr_count($content, '|')) {
            $niceContent = explode(' ', $content);
            foreach ($niceContent as $pos => $word) {
                if (substr_count($word, '|')) {
                    $word = explode('|', $word);
                    $niceContent[$pos] = str_replace('_', ' ', $word[0]);
                }
            }
            $niceContent = implode(' ', $niceContent);
        } else {
            $niceContent = $content;
        }

        $jsonContent = strtolower($content);
        $jsonContent = preg_replace('/[^\w\s-_|]*/', '', $jsonContent);
        $jsonContent = str_replace(' - ', ' ', $jsonContent);
        $jsonContent = explode(' ', $jsonContent);
        foreach ($jsonContent as $key => $word) {
            if (strpos($word, '|')) {
                $jsonContent[$key] = [];

                $word = str_replace('_', ' ', $word);
                $word = explode('|', $word);
                usort($word, function ($a, $b){
                    $wordCountA = substr_count($a, ' ');
                    $wordCountB = substr_count($b, ' ');

                    return $wordCountB - $wordCountA;
                });

                foreach ($word as $k => $w) {
                    if (substr_count ($w, ' ')) {
                        $w = explode(' ', $w);
                    }

                    $jsonContent[$key][$k] = $w;
                }
            }
        }

        return [
            'niceContent' => $niceContent,
            'jsonContent' => $jsonContent
        ];
    }
}
