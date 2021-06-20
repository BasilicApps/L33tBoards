<?php

namespace App\Entity;

use App\Repository\UserBoardVoteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserBoardVoteRepository::class)
 */
class UserBoardVote
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="userBoardVotes")
     */
    private $user;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, inversedBy="userBoardVotes")
     */
    private $board;

    /**
     * @ORM\Column(type="boolean")
     */
    private $liked;

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->board = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection|Board[]
     */
    public function getBoard(): Collection
    {
        return $this->board;
    }

    public function addBoard(Board $board): self
    {
        if (!$this->board->contains($board)) {
            $this->board[] = $board;
        }

        return $this;
    }

    public function removeBoard(Board $board): self
    {
        $this->board->removeElement($board);

        return $this;
    }

    public function getLiked(): ?bool
    {
        return $this->liked;
    }

    public function setLiked(bool $liked): self
    {
        $this->liked = $liked;

        return $this;
    }
}
