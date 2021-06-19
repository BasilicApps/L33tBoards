<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{


    private $posts;
    private $boards;


    /**
     * @Route("/", name="home")
     */
    public function index(PostRepository $postRepository): Response
    {
        $this->postRepository = $postRepository;
        $this->posts = $this->postRepository->find3MostTrending();
        if($this->getUser() != null){
            $this->boards = $this->getUser()->getFollowedBoards();
            dump($this->getUser());
        }else{
            $this->boards = null;
        }
        dump($this->posts);
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'boards' => $this->boards,
            'trendingPosts' => $this->posts


        ]);
    }

    /**
     * @Route("/language/{locale}", name="updateLocale")
     */
    public function updateLocale($locale, Request $request) {
        $request->getSession()->set('_locale', $locale);
        $request->setLocale($locale);
        return $this->redirect($request->headers->get('referer'));
    }
}
