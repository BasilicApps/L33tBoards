<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;

class PostController extends AbstractController
{
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }


    /**
     * @Route("/post", name="post")
     */
    public function index(): Response
    {
        return $this->render('post/index.html.twig', [
            'controller_name' => 'PostController',
        ]);
    }

        /**
     * @Route("/post/{id}", name="showPost", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $post = $this->postRepository->findById($id)[0];
        dump($post);
    
        return $this->render('post/show.html.twig', [
            'post' => $post
        ]);
    }
}
