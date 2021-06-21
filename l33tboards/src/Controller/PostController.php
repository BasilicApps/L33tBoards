<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;

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

    /**
     * @Route("/upvotepost/{postId}", name="upvotepost")
     */
    public function upVotePost(Request $request, EntityManagerInterface $em, int $postId) {
        // Récupération du board et de l'utilisateur connecté
        $post = $this->postRepository->findById($postId)[0];
        $user = $this->getUser();

        // On supprime le downvote éventuel de l'utilisateur pour ce même post
        if ($post->getUserDislikes()->contains($user)) {
            $post->removeUserDislike($user);
            $post->setScore($post->getScore() + 1);
            $user->removeDislikedPost($post);
        }

        // Ajout du upvote utilisateur pour le post
        $post->addUserLike($user);
        $post->setScore($post->getScore() + 1);
        $user->addLikedPost($post);

        // Enregistrement des entités modifiées en BDD
        $em->persist($post);
        $em->persist($user);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/downvotepost/{postId}", name="downvotepost")
     */
    public function downVotePost(Request $request, EntityManagerInterface $em, int $postId) {
        // Récupération du board et de l'utilisateur connecté
        $post = $this->postRepository->findById($postId)[0];
        $user = $this->getUser();

        // On supprime le upvote éventuel de l'utilisateur pour ce même post
        if ($post->getUserLikes()->contains($user)) {
            $post->removeUserLike($user);
            $post->setScore($post->getScore() - 1);
            $user->removeLikedPost($post);
        }

        // Ajout du downvote utilisateur pour le post
        $post->addUserDislike($user);
        $post->setScore($post->getScore() - 1);
        $user->addDislikedPost($post);

        // Enregistrement des entités modifiées en BDD
        $em->persist($post);
        $em->persist($user);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/novotepost/{postId}", name="novotepost")
     */
    public function noVotePost(Request $request, EntityManagerInterface $em, int $postId) {
        // Récupération du board et de l'utilisateur connecté
        $post = $this->postRepository->findById($postId)[0];
        $user = $this->getUser();

        // Suppression du up/down vote selon s'il s'agissait d'un upvote ou d'un downvote initialement (post)
        if ($post->getUserLikes()->contains($user)) {
            // Initialement upvoted (post)
            $post->removeUserLike($user);
            $post->setScore($post->getScore() - 1);
            $user->removeLikedPost($post);
        } else {
            // Initialement downvoted (post)
            $post->removeUserDislike($user);
            $post->setScore($post->getScore() + 1);
            $user->removeDislikedPost($post);
        }

        // Enregistrement des entités modifiées en BDD
        $em->persist($post);
        $em->persist($user);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }
}
