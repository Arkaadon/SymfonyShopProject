<?php

namespace App\Entity;

use App\Repository\DiscountRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DiscountRepository::class)
 */
class Discount
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
    private $DiscountCode;

    /**
     * @ORM\Column(type="integer")
     */
    private $Discount;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiscountCode(): ?string
    {
        return $this->DiscountCode;
    }

    public function setDiscountCode(string $DiscountCode): self
    {
        $this->DiscountCode = $DiscountCode;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->Discount;
    }

    public function setDiscount(int $Discount): self
    {
        $this->Discount = $Discount;

        return $this;
    }
}
