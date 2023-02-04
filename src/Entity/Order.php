<?php

namespace App\Entity;

use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 */
class Order
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $payment_intent;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customId;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sendedAt;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $option1;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $option2;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $option3;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $bankedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $orderId;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\ManyToOne(targetEntity=StripeTransaction::class, inversedBy="commande")
     */
    private $stripeTransaction;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPaymentIntent(): ?string
    {
        return $this->payment_intent;
    }

    public function setPaymentIntent(?string $payment_intent): self
    {
        $this->payment_intent = $payment_intent;

        return $this;
    }

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSendedAt(): ?\DateTimeInterface
    {
        return $this->sendedAt;
    }

    public function setSendedAt(\DateTimeInterface $sendedAt): self
    {
        $this->sendedAt = $sendedAt;

        return $this;
    }

    public function isOption1(): ?bool
    {
        return $this->option1;
    }

    public function setOption1(?bool $option1): self
    {
        $this->option1 = $option1;

        return $this;
    }

    public function isOption2(): ?bool
    {
        return $this->option2;
    }

    public function setOption2(?bool $option2): self
    {
        $this->option2 = $option2;

        return $this;
    }

    public function isOption3(): ?bool
    {
        return $this->option3;
    }

    public function setOption3(?bool $option3): self
    {
        $this->option3 = $option3;

        return $this;
    }

    public function getBankedAt(): ?\DateTimeInterface
    {
        return $this->bankedAt;
    }

    public function setBankedAt(?\DateTimeInterface $bankedAt): self
    {
        $this->bankedAt = $bankedAt;

        return $this;
    }

    public function getOrderId(): ?string
    {
        return $this->orderId;
    }

    public function setOrderId(?string $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getStripeTransaction(): ?StripeTransaction
    {
        return $this->stripeTransaction;
    }

    public function setStripeTransaction(?StripeTransaction $stripeTransaction): self
    {
        $this->stripeTransaction = $stripeTransaction;

        return $this;
    }
}
