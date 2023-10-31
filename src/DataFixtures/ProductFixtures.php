<?php

namespace App\DataFixtures;

use App\Entity\Brand;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands = $manager->getRepository(Brand::class)->findAll();
        $categories = $manager->getRepository(Category::class)->findAll();

        $faker = \Faker\Factory::create();

        $genders = ['Male', 'Female'];

        for ($i = 0; $i < 20; $i++) {
            $product = new Product();
            $product->setName('Product ' . $i);
            $product->setPrice($faker->numberBetween(10, 100));
            $product->setColor($faker->colorName());
            $product->setSize('Size ' . $i);
            $product->setBrand($brands[array_rand($brands)]);
            $product->setCategory($categories[array_rand($categories)]);
            $product->setStockQuantity($faker->numberBetween(10, 40));
            $product->setImage($faker->image());
            $product->setGender($genders[array_rand($genders)]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
