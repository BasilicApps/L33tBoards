<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        if($this->getUser() != null){
            $boards = $this->getUser()->getFollowedBoards();
            dump($boards);
        }else{
            $boards = null;
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'boards' => 'boards'
        ]);
    }
}
