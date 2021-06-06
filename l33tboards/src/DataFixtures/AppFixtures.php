<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Board;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $faker = Faker\Factory::create();

        for ($i = 0; $i < 3; $i++) { //users
            $user = new User();
            $user->setUsername($faker->firstName());
            $user->setPassword($faker->stateAbbr()); //note that this is the hashed password so what is it doesn't really matter, you wont be able to log anyway

            $manager->persist($user);

            for ($u = 0; $u < 3; $u++) { //boards
                $board = new Board();
                $board->setTitle($faker->sentence(3));
                $board->setUrlTitle($board->getTitle());
                $board->setVisibility(true);
                $board->setScore(0);
                $board->addOwner($user);

                $manager->persist($board);
            
                
                for ($b = 0; $b < 3; $b++) { //posts
                    $post = new Post();
                    $post->setTitle($faker->sentence());
                    $post->setContent($faker->paragraph());
                    $post->setScore(0);
                    $post->setAuthor($user);
                    $post->setBoard($board);

                    $manager->persist($post);
                }
            }
        }
        $manager->flush();
    }
}
