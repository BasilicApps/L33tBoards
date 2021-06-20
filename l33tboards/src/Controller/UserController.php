<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Repository\UserRepository;

class UserController extends AbstractController
{

    private $userRepository;
    private $boards;


    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/u", name="user")
     */
    public function index(): Response
    {
    	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

        /**
     * @Route("/u/profil/{username}", name="profileUser")
     */
    public function profile(User $user): Response
    {
    	$this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $user = $this->getUser();
        if($this->getUser() != null){
            $this->boards = $this->getUser()->getFollowedBoards();
            dump($this->getUser());
        }else{
            $this->boards = null;
        }
        return $this->render('user/profile.html.twig', [
            'user' => $user,
            'boards' => $this->boards
        ]);
    }

    /**
     * @Route("/u/{username}", name="showUser", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user
        ]);
    }
}
