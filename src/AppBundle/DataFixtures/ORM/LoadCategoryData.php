<?php
/**
 * Created by PhpStorm.
 * User: huynguyen
 * Date: 4/14/17
 * Time: 3:08 PM
 */

namespace AppBundle\DataFixtures\ORM;


use AppBundle\Entity\Category;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('English Stories');
        $category->setSlug('english-stories');
        $category->setPosition(1);

        $category2 = new Category();
        $category2->setName('Callan Method');
        $category2->setSlug('callan-method');
        $category2->setPosition(2);

        $manager->persist($category);
        $manager->persist($category2);
        $manager->flush();
    }
}