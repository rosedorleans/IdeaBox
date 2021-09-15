<?php

namespace App\Entity;

use App\Repository\IdeaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IdeaRepository::class)
 */
class Idea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="ideas")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\OneToMany(targetEntity=IdeaLike::class, mappedBy="idea")
     */
    private $users;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="dislike")
     */
    private $dislike;


    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->dislike = new ArrayCollection();
    }




    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor(User $author): self
    {
        $this->author = $author;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeLike($this);
        }

        return $this;
    }


    /**
     * Permet de savoir si l'idée est likée par l'utilisateur connecté
     * @param User $userEntity
     * @return bool
     */
    public function isLikedByUser(User $userEntity): bool {
        foreach ($this->users as $user) {
            if ($user->getUsername() === $userEntity) return true;
        }
        return false;
    }
    /**
     * Permet de savoir si l'idée est dislikée par l'utilisateur connecté
     * @param User $userEntity
     * @return bool
     */
    public function isDislikedByUser(User $userEntity): bool {
        foreach ($this->dislike as $user) {
            if ($user->getUsername() === $userEntity) return true;
        }
        return false;
    }

    /**
     * @return Collection|User[]
     */
    public function getDislike(): Collection
    {
        return $this->dislike;
    }

    public function addDislike(User $dislike): self
    {
        if (!$this->dislike->contains($dislike)) {
            $this->dislike[] = $dislike;
        }

        return $this;
    }

    public function removeDislike(User $dislike): self
    {
        $this->dislike->removeElement($dislike);

        return $this;
    }




}
