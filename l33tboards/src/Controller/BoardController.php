<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Board;
use App\Repository\BoardRepository;
use Doctrine\ORM\EntityManagerInterface;

use App\Form\BoardFormType;

class BoardController extends AbstractController
{

    private $boardRepository;
    private $boards;




    public function __construct(BoardRepository $boardRepository)
    {
        $this->boardRepository = $boardRepository;
        $this->boards = $this->boardRepository->findAll();
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

    /**
     * @Route("/newboard", name="createBoard")
     */
    public function create(Request $request): Response
    {
        $boardForm = $this->createForm(BoardFormType::class);
        $boardForm->handleRequest($request);

        if ($boardForm->isSubmitted() && $boardForm->isValid())
        {

            /** @var Board $board */
            $board = $boardForm->getData();
            $board
                ->addOwner($this->getUser())
                ->setScore(0)
                ->setUrlTitle($board->getTitle())
            ;

            // TODO : prevent board dupe
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($board);
            $manager->flush();

            return $this->redirectToRoute('follow', ['urlTitle' => $board->getUrlTitle()]);
        }

        return $this->render('board/create.html.twig', [
            'boardForm' => $boardForm->createView()
        ]);
    }
}