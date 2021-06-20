<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\PostRepository;

use App\Form\PostFormType;
use App\Form\CommentFormType;

use App\Entity\Comment;

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
    public function index(Request $request): Response
    {
        $postForm = $this->createForm(PostFormType::class);
        $postForm->handleRequest($request);

        if ($postForm->isSubmitted() && $postForm->isValid())
        {

            /** @var Post $post */
            $post = $postForm->getData();
            $post
                ->setScore(0)
                ->setAuthor($this->getUser())
                ->setCreatedAt(new \DateTime())
            ;
            $post->getBoard()->addPost($post);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->persist($post->getBoard());
            $manager->flush();

            return $this->redirectToRoute('showBoard', ['urlTitle' => $post->getBoard()->getUrlTitle()]);
        }

        return $this->render('post/index.html.twig', [
            'postForm' => $postForm->createView()
        ]);
    }

        /**
     * @Route("/post/{id}", name="showPost")
     */
    public function show(int $id, Request $request): Response
    {
        $post = $this->postRepository->findById($id)[0];
        
        $commentForm = $this->createForm(CommentFormType::class);
        $commentForm->handleRequest($request);
        
        if ($commentForm->isSubmitted() && $commentForm->isValid())
        {
            /** @var Comment $comment */
            $comment = $commentForm->getData();
            $comment
                ->setAuthor($this->getUser())
                ->setCreatedAt(new \DateTime())
            ;
            $comment->setPost($post);
            $post->addComment($comment);

            $manager = $this->getDoctrine()->getManager();
            $manager->persist($post);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirect($request->getUri());
        }
    
        return $this->render('post/show.html.twig', [
            'post' => $post,
            'commentForm' => $commentForm->createView()
        ]);
    }
}
