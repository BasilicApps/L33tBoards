<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 3; $i++) {
            $user = new User();
            $user->setUsername($faker->firstName());
            $user->setPassword($faker->stateAbbr()); //note that this is the hashed password so what is it doesn't really matter 

            $manager->persist($user);
        }

        $manager->flush();
    }
}
