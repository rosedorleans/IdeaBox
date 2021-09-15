<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
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
    private $email;

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
     * @ORM\OneToMany(targetEntity=Idea::class, mappedBy="author")
     */
    private $ideas;

//    /**
//     * @ORM\OneToMany(targetEntity=IdeaLike::class, mappedBy="user")
//     */
//    private $likes;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $pseudo;

    /**
     * @ORM\ManyToMany(targetEntity=Idea::class, inversedBy="users")
     */
    private $likes;

    /**
     * @ORM\ManyToMany(targetEntity=Idea::class, mappedBy="dislike")
     */
    private $dislike;




    public function __construct()
    {
        $this->ideas = new ArrayCollection();
        $this->author = new ArrayCollection();
        $this->likes = new ArrayCollection();
        $this->dislike = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
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
     * @return Collection|Idea[]
     */
    public function getIdeas(): Collection
    {
        return $this->ideas;
    }


    public function __toString()
    {
        return $this->email;
    }


    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * @return Collection|Idea[]
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Idea $like): self
    {
        if (!$this->likes->contains($like)) {
            $this->likes[] = $like;
        }

        return $this;
    }

    public function removeLike(Idea $like): self
    {
        $this->likes->removeElement($like);

        return $this;
    }

    /**
     * @return Collection|Idea[]
     */
    public function getDislike(): Collection
    {
        return $this->dislike;
    }

    public function addDislike(Idea $dislike): self
    {
        if (!$this->dislike->contains($dislike)) {
            $this->dislike[] = $dislike;
            $dislike->addDislike($this);
        }

        return $this;
    }

    public function removeDislike(Idea $dislike): self
    {
        if ($this->dislike->removeElement($dislike)) {
            $dislike->removeDislike($this);
        }

        return $this;
    }



}
