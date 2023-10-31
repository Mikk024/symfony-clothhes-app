<?php


namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create();

        for ($i = 0; $i < 20; $i++) {
            $user = new User();
            $user->setEmail($faker->email());
            $user->setPassword($faker->password());
            $user->setIsVerified(true);

            $address = new Address();
            $address->setStreet($faker->streetName());
            $address->setCity($faker->city());
            $address->setState($faker->word());
            $address->setPostcode($faker->postcode());
            $address->setCountry($faker->country());
            $address->setFirstName($faker->firstName());
            $address->setLastName($faker->lastName());

            $user->setAddress($address);

            $manager->persist($user);
        }

        $manager->flush();
    }
}



?>