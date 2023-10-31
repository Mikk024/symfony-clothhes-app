<?php

namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
;

class AdminFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        $address = new Address();
        $address->setStreet($faker->address());
        $address->setCity($faker->city());
        $address->setState($faker->word());
        $address->setPostcode($faker->postcode());
        $address->setCountry($faker->country());
        $address->setFirstName($faker->firstName());
        $address->setLastName($faker->lastName());

        $user = new User();
        $user->setEmail('admin@admin.com');
        $user->setPassword($faker->password());
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
        $user->setIsVerified(true);
        $user->setAddress($address);
    
        $manager->persist($user);
        $manager->flush();
    }
}
