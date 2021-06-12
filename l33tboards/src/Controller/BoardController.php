<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Board;
use App\Repository\BoardRepository;

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
     * @Route("/toggleFollow/{urlTitle}", name="toggleFollow", methods={"GET"})
     */

    public function toggleFollow(string $urlTitle): Response
    {
        $board = $this->boardRepository->findByUrl($urlTitle)[0];
        dump($board);
        $this->getUser()->addFollowedBoard($board);
        return $this->render('board/show.html.twig', [
            'board' => $board
        ]);
    }


    /**
     * @Route("/boards/{urlTitle}", name="showBoard", methods={"GET"})
     */
    public function show(string $urlTitle): Response
    {
        $board = $this->boardRepository->findByUrl($urlTitle)[0];
        dump($board);
    
        return $this->render('board/show.html.twig', [
            'board' => $board
        ]);
    }
}