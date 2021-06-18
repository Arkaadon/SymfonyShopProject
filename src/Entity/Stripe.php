<?php

namespace App\Entity;

use App\Repository\StripeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StripeRepository::class)
 */
class Stripe
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
    private $SessionId;

    /**
     * @ORM\Column(type="boolean")
     */
    private $PaymentStatus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="stripes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): ?string
    {
        return $this->SessionId;
    }

    public function setSessionId(string $SessionId): self
    {
        $this->SessionId = $SessionId;

        return $this;
    }

    public function getPaymentStatus(): ?bool
    {
        return $this->PaymentStatus;
    }

    public function setPaymentStatus(bool $PaymentStatus): self
    {
        $this->PaymentStatus = $PaymentStatus;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
