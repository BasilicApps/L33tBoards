<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Board;
use App\Repository\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;

class BoardController extends AbstractController
{

    private $boardRepository;
    private $boards;

    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
        $this->boards = $this->boardRepository->findLast(10);
    }

    /**
     * @Route("/boards", name="boards", methods={"GET"})
     */
    public function index(): Response
    {
        return $this->render('board/index.html.twig', [
            'boards' => $this->boards
        ]);
    }

    /**
     * @Route("/follow/{boardId}", name="follow", methods={"GET"})
     */
    public function follow(Request $request, string $boardId, EntityManagerInterface $em): Response
    {
        $board = $this->boardRepository->findById($boardId)[0];

        $this->getUser()->addFollowedBoard($board);
        $board->addFollowingUser($this->getUser());

        $em->persist($board);
        $em->persist($this->getUser());
        $em->flush();

        dump($this->getUser());
        dump($board);

        return $this->redirect($request->headers->get('referer'));
        /*return $this->render('board/show.html.twig', [
            'board' => $board
        ]);*/
    }

    /**
     * @Route("/unFollow/{boardId}", name="unFollow", methods={"GET"})
     */
    public function unFollow(Request $request, string $boardId, EntityManagerInterface $em): Response
    {
        $board = $this->boardRepository->findById($boardId)[0];

        $this->getUser()->removeFollowedBoard($board);
        $board->removeFollowingUser($this->getUser());

        $em->persist($board);
        $em->persist($this->getUser());
        $em->flush();

        dump($this->getUser());
        dump($board);

        return $this->redirect($request->headers->get('referer'));
        /*return $this->render('board/show.html.twig', [
            'board' => $board
        ]);*/
    }

    /**
     * @Route("/boards/upvote/{boardId}", name="upvoteboard", methods={"GET"})
     */
    public function upVoteBoard(Request $request, EntityManagerInterface $em, string $boardId): Response {
        // Récupération du board et de l'utilisateur connecté
        $board = $this->boardRepository->findById($boardId)[0];
        $user = $this->getUser();

        // On supprime le downvote éventuel de l'utilisateur pour ce même board
        if ($board->getUserDislikes()->contains($user)) {
            $board->removeUserDislike($user);
            $board->setScore($board->getScore() + 1);
            $user->removeDislikedBoard($board);
        }

        // Ajout du upvote utilisateur pour le board
        $board->addUserLike($user);
        $board->setScore($board->getScore() + 1);
        $user->addLikedBoard($board);

        // Enregistrement des entités modifiées en BDD
        $em->persist($board);
        $em->persist($user);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/board/downvote/{boardId}", name="downvoteboard", methods={"GET"})
     */
    public function downVoteBoard(Request $request, EntityManagerInterface $em, string $boardId): Response {
        // Récupération du board et de l'utilisateur connecté
        $board = $this->boardRepository->findById($boardId)[0];
        $user = $this->getUser();

        // On supprime le upvote éventuel de l'utilisateur pour ce même board
        if ($board->getUserLikes()->contains($user)) {
            $board->removeUserLike($user);
            $board->setScore($board->getScore() - 1);
            $user->removeLikedBoard($board);
        }

        // Ajout du downvote utilisateur pour le board
        $board->addUserDislike($user);
        $board->setScore($board->getScore() - 1);
        $user->addDislikedBoard($board);

        // Enregistrement des entités modifiées en BDD
        $em->persist($board);
        $em->persist($user);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/board/novote/{boardId}", name="novoteboard", methods={"GET"})
     */
    public function noVoteBoard(Request $request, EntityManagerInterface $em, string $boardId): Response {
        // Récupération du board et de l'utilisateur connecté
        $board = $this->boardRepository->findById($boardId)[0];
        $user = $this->getUser();

        // Suppression du up/down vote selon s'il s'agissait d'un upvote ou d'un downvote initialement
        if ($board->getUserLikes()->contains($user)) {
            // Initialement upvoted
            $board->removeUserLike($user);
            $board->setScore($board->getScore() - 1);
            $user->removeLikedBoard($board);
        } else {
            // Initialement downvoted
            $board->removeUserDislike($user);
            $board->setScore($board->getScore() + 1);
            $user->removeDislikedBoard($board);
        }

        // Enregistrement des entités modifiées en BDD
        $em->persist($board);
        $em->persist($user);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/boards/{urlTitle}", name="showBoard", methods={"GET"})
     */
    public function show(string $urlTitle): Response
    {
        $board = $this->boardRepository->findByUrl($urlTitle)[0];
    
        return $this->render('board/show.html.twig', [
            'board' => $board
        ]);
    }
}