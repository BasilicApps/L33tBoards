<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author", orphanRemoval=true)
     */
    private $posts;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, mappedBy="owner")
     */
    private $boards;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, inversedBy="followingUsers")
     */
    private $followedBoards;

    /**
     * @ORM\OneToMany(targetEntity=Comment::class, mappedBy="author", orphanRemoval=true)
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, mappedBy="userLikes")
     */
    private $likedBoards;

    /**
     * @ORM\ManyToMany(targetEntity=Board::class, mappedBy="userDislikes")
     */
    private $dislikedBoards;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="userLikes")
     */
    private $likedPosts;

    /**
     * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="userDislikes")
     */
    private $dislikedPosts;

    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->boards = new ArrayCollection();
        $this->followedBoards = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->likedBoards = new ArrayCollection();
        $this->dislikedBoards = new ArrayCollection();
        $this->likedPosts = new ArrayCollection();
        $this->dislikedPosts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Board[]
     */
    public function getBoards(): Collection
    {
        return $this->boards;
    }

    public function addBoard(Board $board): self
    {
        if (!$this->boards->contains($board)) {
            $this->boards[] = $board;
            $board->addOwner($this);
        }

        return $this;
    }

    public function removeBoard(Board $board): self
    {
        if ($this->boards->removeElement($board)) {
            $board->removeOwner($this);
        }

        return $this;
    }

    /**
     * @return Collection|Board[]
     */
    public function getFollowedBoards(): Collection
    {
        return $this->followedBoards;
    }

    public function addFollowedBoard(Board $followedBoard): self
    {
        if (!$this->followedBoards->contains($followedBoard)) {
            $this->followedBoards[] = $followedBoard;
        }

        return $this;
    }

    public function removeFollowedBoard(Board $followedBoard): self
    {
        $this->followedBoards->removeElement($followedBoard);

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setAuthor($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getAuthor() === $this) {
                $comment->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Board[]
     */
    public function getLikedBoards(): Collection
    {
        return $this->likedBoards;
    }

    public function addLikedBoard(Board $likedBoard): self
    {
        if (!$this->likedBoards->contains($likedBoard)) {
            $this->likedBoards[] = $likedBoard;
            $likedBoard->addUserLike($this);
        }

        return $this;
    }

    public function removeLikedBoard(Board $likedBoard): self
    {
        if ($this->likedBoards->removeElement($likedBoard)) {
            $likedBoard->removeUserLike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Board[]
     */
    public function getDislikedBoards(): Collection
    {
        return $this->dislikedBoards;
    }

    public function addDislikedBoard(Board $dislikedBoard): self
    {
        if (!$this->dislikedBoards->contains($dislikedBoard)) {
            $this->dislikedBoards[] = $dislikedBoard;
            $dislikedBoard->addUserDislike($this);
        }

        return $this;
    }

    public function removeDislikedBoard(Board $dislikedBoard): self
    {
        if ($this->dislikedBoards->removeElement($dislikedBoard)) {
            $dislikedBoard->removeUserDislike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getLikedPosts(): Collection
    {
        return $this->likedPosts;
    }

    public function addLikedPost(Post $likedPost): self
    {
        if (!$this->likedPosts->contains($likedPost)) {
            $this->likedPosts[] = $likedPost;
            $likedPost->addUserLike($this);
        }

        return $this;
    }

    public function removeLikedPost(Post $likedPost): self
    {
        if ($this->likedPosts->removeElement($likedPost)) {
            $likedPost->removeUserLike($this);
        }

        return $this;
    }

    /**
     * @return Collection|Post[]
     */
    public function getDislikedPosts(): Collection
    {
        return $this->dislikedPosts;
    }

    public function addDislikedPost(Post $dislikedPost): self
    {
        if (!$this->dislikedPosts->contains($dislikedPost)) {
            $this->dislikedPosts[] = $dislikedPost;
            $dislikedPost->addUserDislike($this);
        }

        return $this;
    }

    public function removeDislikedPost(Post $dislikedPost): self
    {
        if ($this->dislikedPosts->removeElement($dislikedPost)) {
            $dislikedPost->removeUserDislike($this);
        }

        return $this;
    }
}
