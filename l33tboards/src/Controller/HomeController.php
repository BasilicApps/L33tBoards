<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    private $boards;
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        if($this->getUser() != null){
            $this->boards = $this->getUser()->getFollowedBoards();
            dump($this->getUser());
        }else{
            $this->boards = null;
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'boards' => $this->boards
        ]);
    }
}
