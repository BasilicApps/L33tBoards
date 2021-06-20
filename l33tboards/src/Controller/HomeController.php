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
     * @Route("/search", name="search")
     */
    public function Search(Request $request)
    {
        $data = $request->request->all();

        $temp = "%";
        dump($data["search"]);

        if($data["search"] != null){
            $temp = "%".$data["search"]."%";
        }

        $em = $this->getDoctrine()->getManager();
        $query = $em->createQueryBuilder()->select('b')
        ->from('App:Board','b')
        ->andWhere('b.title LIKE :string')
        ->setParameter('string',$temp)
        ->getQuery();


        $res = $query->execute();

        return $this->render('board/index.html.twig', [
            'boards' => $res
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
