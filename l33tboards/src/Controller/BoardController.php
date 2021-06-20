<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Board;
use App\Entity\UserBoardVote;
use App\Repository\BoardRepository;
use App\Repository\UserBoardVoteRepository;
use Doctrine\ORM\EntityManagerInterface;

class BoardController extends AbstractController
{

    private $boardRepository;
    private $userBoardVoteRepository;
    private $boards;

    public function __construct(BoardRepository $boardRepository, UserBoardVoteRepository $userBoardVoteRepository)
    {
        $this->boardRepository = $boardRepository;
        $this->userBoardVoteRepository = $userBoardVoteRepository;
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
     * @Route("/follow/{urlTitle}", name="follow", methods={"GET"})
     */

    public function follow(string $urlTitle, EntityManagerInterface $em): Response
    {
        $board = $this->boardRepository->findByUrl($urlTitle)[0];

        $this->getUser()->addFollowedBoard($board);
        $board->addFollowingUser($this->getUser());

        $em->persist($board);
        $em->persist($this->getUser());
        $em->flush();

        dump($this->getUser());
        dump($board);
        return $this->render('board/show.html.twig', [
            'board' => $board
        ]);
    }

        /**
     * @Route("/unFollow/{urlTitle}", name="unFollow", methods={"GET"})
     */

    public function unFollow(string $urlTitle, EntityManagerInterface $em): Response
    {
        $board = $this->boardRepository->findByUrl($urlTitle)[0];

        $this->getUser()->removeFollowedBoard($board);
        $board->removeFollowingUser($this->getUser());

        $em->persist($board);
        $em->persist($this->getUser());
        $em->flush();

        dump($this->getUser());
        dump($board);
        return $this->render('board/show.html.twig', [
            'board' => $board
        ]);
    }

    public function upVoteBoard(Request $request, EntityManagerInterface $em, string $boardUrlTitle): Response {
        // Récupération du board et de l'utilisateur connecté
        $board = $this->boardRepository->findByUrl($boardUrlTitle)[0];
        $user = $this->getUser();

        // Génération d'une info. d'upvote utilisateur pour un board spécifique
        $uBoardVote = new UserBoardVote();
        $uBoardVote->addUser($user);
        $uBoardVote->addBoard($board);
        $uBoardVote->setLiked(true);

        // Association user/board avec l'user/board/vote
        $user->addUserBoardVote($uBoardVote);
        $board->addUserBoardVote($uBoardVote);

        // Enregistrement des entités modifiées en BDD
        $em->persist($board);
        $em->persist($user);
        $em->persist($uBoardVote);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    public function downVoteBoard(Request $request, EntityManagerInterface $em, string $boardUrlTitle): Response {
        // Récupération du board et de l'utilisateur connecté
        $board = $this->boardRepository->findByUrl($boardUrlTitle)[0];
        $user = $this->getUser();

        // Génération d'une info. d'upvote utilisateur pour un board spécifique
        $uBoardVote = new UserBoardVote();
        $uBoardVote->addUser($user);
        $uBoardVote->addBoard($board);
        $uBoardVote->setLiked(false);

        // Association user/board avec l'user/board/vote
        $user->addUserBoardVote($uBoardVote);
        $board->addUserBoardVote($uBoardVote);

        // Enregistrement des entités modifiées en BDD
        $em->persist($board);
        $em->persist($user);
        $em->persist($uBoardVote);
        $em->flush();

        // Redirection auto vers la page précédente
        return $this->redirect($request->headers->get('referer'));
    }

    public function noVoteBoard(Request $request, EntityManagerInterface $em, string $boardUrlTitle): Response {
        // Récupération du board et de l'utilisateur connecté
        $board = $this->boardRepository->findByUrl($boardUrlTitle)[0];
        $user = $this->getUser();

        // Récupération de l'association user/board et du vote associé
        $uBoardVote = $this->userBoardVoteRepository->findByUserAndBoard($user, $board)[0];

        // Association user/board avec l'user/board/vote
        $user->removeUserBoardVote($uBoardVote);
        $board->removeUserBoardVote($uBoardVote);

        // Enregistrement des entités modifiées en BDD
        $em->persist($board);
        $em->persist($user);
        $em->remove($uBoardVote);
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