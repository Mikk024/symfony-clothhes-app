<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeOne = new Type();
        $typeOne->setName('Clothes');
        $typeTwo = new Type();
        $typeTwo->setName('Shoes');

        $manager->persist($typeOne);
        $manager->persist($typeTwo);

        $types = [$typeOne, $typeTwo];

        for ($i = 0; $i < 10; $i++) {
            $category = new Category();
            $category->setName('Category ' . $i);
            $category->setType($types[array_rand($types)]);
            $manager->persist($category);
        }

        $manager->flush();
    }
}
