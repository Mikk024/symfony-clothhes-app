<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class BrandFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 10; $i++) {
            $brand = new Brand();
            $brand->setName('Brand' . $i);
            $manager->persist($brand);
        }

        $manager->flush();
    }
}
