<?php

namespace App\Entity;

use App\Repository\BoardRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BoardRepository::class)
 */
class Board
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="boards")
     */
    private $owner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="boolean")
     */
    private $visibility;

    /**
     * @ORM\Column(type="integer")
     */
    private $score;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="board", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $urlTitle;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="followedBoards")
     */
    private $followingUsers;

    /**
     * @ORM\ManyToMany(targetEntity=UserBoardVote::class, mappedBy="board")
     */
    private $userBoardVotes;

    public function __construct()
    {
        $this->owner = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->followingUsers = new ArrayCollection();
        $this->userBoardVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|User[]
     */
    public function getOwner(): Collection
    {
        return $this->owner;
    }

    public function addOwner(User $owner): self
    {
        if (!$this->owner->contains($owner)) {
            $this->owner[] = $owner;
        }

        return $this;
    }

    public function removeOwner(User $owner): self
    {
        $this->owner->removeElement($owner);

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getVisibility(): ?bool
    {
        return $this->visibility;
    }

    public function setVisibility(bool $visibility): self
    {
        $this->visibility = $visibility;

        return $this;
    }

    public function getScore(): ?int
    {
        return $this->score;
    }

    public function setScore(int $score): self
    {
        $this->score = $score;

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setBoard($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getBoard() === $this) {
                $post->setBoard(null);
            }
        }

        return $this;
    }

    public function getUrlTitle(): ?string
    {
        return $this->urlTitle;
    }

    public function setUrlTitle(string $urlTitle): self
    {
        // On vire tous les espaces
        $urlTitle = preg_replace("/\s+/", "", $urlTitle);

        $this->urlTitle = $urlTitle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getFollowingUsers(): Collection
    {
        return $this->followingUsers;
    }

    public function addFollowingUser(User $followingUser): self
    {
        if (!$this->followingUsers->contains($followingUser)) {
            $this->followingUsers[] = $followingUser;
            $followingUser->addFollowedBoard($this);
        }

        return $this;
    }

    public function removeFollowingUser(User $followingUser): self
    {
        if ($this->followingUsers->removeElement($followingUser)) {
            $followingUser->removeFollowedBoard($this);
        }

        return $this;
    }

    /**
     * @return Collection|UserBoardVote[]
     */
    public function getUserBoardVotes(): Collection
    {
        return $this->userBoardVotes;
    }

    public function addUserBoardVote(UserBoardVote $userBoardVote): self
    {
        if (!$this->userBoardVotes->contains($userBoardVote)) {
            $this->userBoardVotes[] = $userBoardVote;
            $userBoardVote->addBoard($this);
        }

        return $this;
    }

    public function removeUserBoardVote(UserBoardVote $userBoardVote): self
    {
        if ($this->userBoardVotes->removeElement($userBoardVote)) {
            $userBoardVote->removeBoard($this);
        }

        return $this;
    }
}
