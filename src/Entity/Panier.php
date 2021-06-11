<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PanierRepository::class)
 */
class Panier
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="panier", cascade={"persist", "remove"})
     */
    private $PanierOfUser;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $TotalPrice;

    /**
     * @ORM\OneToMany(targetEntity=ProductQuantity::class, mappedBy="Panier", orphanRemoval=true)
     */
    private $productQuantities;

    /**
     * @ORM\Column(type="boolean")
     */
    private $DiscountUsed = 0;

    public function __construct()
    {
        $this->productQuantities = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPanierOfUser(): ?User
    {
        return $this->PanierOfUser;
    }

    public function setPanierOfUser(?User $PanierOfUser): self
    {
        $this->PanierOfUser = $PanierOfUser;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->TotalPrice;
    }

    public function setTotalPrice(float $TotalPrice): self
    {
        $this->TotalPrice = $TotalPrice;

        return $this;
    }
    
    /**
     * @return Collection|ProductQuantity[]
     */
    public function getProductQuantities(): Collection
    {
        return $this->productQuantities;
    }

    public function addProductQuantity(ProductQuantity $productQuantity): self
    {
        if (!$this->productQuantities->contains($productQuantity)) {
            $this->productQuantities[] = $productQuantity;
            $productQuantity->setPanier($this);
        }

        return $this;
    }

    public function removeProductQuantity(ProductQuantity $productQuantity): self
    {
        if ($this->productQuantities->removeElement($productQuantity)) {
            // set the owning side to null (unless already changed)
            if ($productQuantity->getPanier() === $this) {
                $productQuantity->setPanier(null);
            }
        }

        return $this;
    }

    public function getDiscountUsed(): ?bool
    {
        return $this->DiscountUsed;
    }

    public function setDiscountUsed(bool $DiscountUsed): self
    {
        $this->DiscountUsed = $DiscountUsed;

        return $this;
    }

}
