<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $posts;

    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository): Response
    {
        $this->postRepository = $postRepository;
        $this->posts = $this->postRepository->find3MostTrending();
        if($this->getUser() != null){
            $boards = $this->getUser()->getFollowedBoards();
            dump($boards);
        }else{
            $boards = null;
        }
        dump($this->posts);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'boards' => $boards,
            'trendingPosts' => $this->posts

        ]);
    }
}
