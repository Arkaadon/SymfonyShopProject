<?php

namespace App\Entity;

use App\Repository\ProductQuantityRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProductQuantityRepository::class)
 */
class ProductQuantity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Panier::class, inversedBy="productQuantities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Panier;

    /**
     * @ORM\ManyToOne(targetEntity=Product::class, inversedBy="productQuantities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Product;

    /**
     * @ORM\Column(type="integer")
     */
    private $Quantity;

    /**
     * @ORM\Column(type="float")
     */
    private $TotalQuantityPrice;

    public function getPanier(): ?Panier
    {
        return $this->Panier;
    }

    public function setPanier(?Panier $Panier): self
    {
        $this->Panier = $Panier;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): self
    {
        $this->Product = $Product;

        return $this;
    }

    public function getQuantity(): ?int
    {
        return $this->Quantity;
    }

    public function setQuantity(int $Quantity): self
    {
        $this->Quantity = $Quantity;

        return $this;
    }

    public function getTotalQuantityPrice(): ?float
    {
        return $this->TotalQuantityPrice;
    }

    public function setTotalQuantityPrice(float $TotalQuantityPrice): self
    {
        $this->TotalQuantityPrice = $TotalQuantityPrice;

        return $this;
    }
}
