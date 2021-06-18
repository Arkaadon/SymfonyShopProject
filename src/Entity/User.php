<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"username"}, message="There is already an account with this username")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
     * @ORM\OneToOne(targetEntity=Panier::class, mappedBy="PanierOfUser", cascade={"persist", "remove"})
     */
    private $panier;

    /**
     * @ORM\OneToMany(targetEntity=Stripe::class, mappedBy="User", orphanRemoval=true)
     */
    private $stripes;

    public function __construct()
    {
        $this->stripes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
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

    public function addRoles($role): self
    {
        $roles = $this->roles;
        array_push($roles, $role);

        return $this;
    }

    public function removeRoles($role): self
    {
       
        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
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

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): self
    {
        // unset the owning side of the relation if necessary
        if ($panier === null && $this->panier !== null) {
            $this->panier->setPanierOfUser(null);
        }

        // set the owning side of the relation if necessary
        if ($panier !== null && $panier->getPanierOfUser() !== $this) {
            $panier->setPanierOfUser($this);
        }

        $this->panier = $panier;

        return $this;
    }

    /**
     * @return Collection|Stripe[]
     */
    public function getStripes(): Collection
    {
        return $this->stripes;
    }

    public function addStripe(Stripe $stripe): self
    {
        if (!$this->stripes->contains($stripe)) {
            $this->stripes[] = $stripe;
            $stripe->setUser($this);
        }

        return $this;
    }

    public function removeStripe(Stripe $stripe): self
    {
        if ($this->stripes->removeElement($stripe)) {
            // set the owning side to null (unless already changed)
            if ($stripe->getUser() === $this) {
                $stripe->setUser(null);
            }
        }

        return $this;
    }
}
