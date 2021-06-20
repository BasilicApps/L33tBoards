<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

use App\Form\PostFormType;

class PostController extends AbstractController
{
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
}
