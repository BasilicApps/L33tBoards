<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Post;
use App\Entity\Board;
use App\Entity\Comment;
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
                //$board->setScore(0);
                $board->setScore($faker->numberBetween(-1000, 9999)); // Pour tester les badges avec une valeur + élevée
                $board->addOwner($user);

                $manager->persist($board);
            
                
                for ($b = 0; $b < 3; $b++) { //posts
                    $post = new Post();
                    $post->setTitle($faker->sentence());
                    $post->setContent($faker->paragraph());
                    //$post->setScore(0);
                    $post->setScore($faker->numberBetween(-1000, 9999)); // Pour tester les badges avec une valeur + élevée
                    $post->setAuthor($user);
                    $post->setBoard($board);
                    $post->setCreatedAt($faker->dateTime());

                    $manager->persist($post);

                    for ($c = 0; $c < 3; $c++) { //comments
                        $com = new Comment();
                        $com->setContent($faker->paragraph());
                        $com->setAuthor($user);
                        $com->setPost($post);
                        $com->setCreatedAt($faker->dateTime());
    
                        $manager->persist($com);
                    }
                }
            }
        }
        $manager->flush();
    }
}
