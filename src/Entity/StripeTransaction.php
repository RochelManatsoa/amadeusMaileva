<?php

namespace App\Entity;

use App\Repository\StripeTransactionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StripeTransactionRepository::class)
 */
class StripeTransaction
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
    private $intentId;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountCapturable;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $amountDetails;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $amountReceived;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $captureMethod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $clientSecret;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $confirmationMethod;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $created;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $currency;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customer;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $invoice;

    /**
     * @ORM\OneToMany(targetEntity=Order::class, mappedBy="stripeTransaction")
     */
    private $commande;

    public function __construct()
    {
        $this->commande = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIntentId(): ?string
    {
        return $this->intentId;
    }

    public function setIntentId(string $intentId): self
    {
        $this->intentId = $intentId;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getAmountCapturable(): ?int
    {
        return $this->amountCapturable;
    }

    public function setAmountCapturable(?int $amountCapturable): self
    {
        $this->amountCapturable = $amountCapturable;

        return $this;
    }

    public function getAmountDetails(): ?string
    {
        return $this->amountDetails;
    }

    public function setAmountDetails(?string $amountDetails): self
    {
        $this->amountDetails = $amountDetails;

        return $this;
    }

    public function getAmountReceived(): ?int
    {
        return $this->amountReceived;
    }

    public function setAmountReceived(?int $amountReceived): self
    {
        $this->amountReceived = $amountReceived;

        return $this;
    }

    public function getCaptureMethod(): ?string
    {
        return $this->captureMethod;
    }

    public function setCaptureMethod(?string $captureMethod): self
    {
        $this->captureMethod = $captureMethod;

        return $this;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    public function setClientSecret(?string $clientSecret): self
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    public function getConfirmationMethod(): ?string
    {
        return $this->confirmationMethod;
    }

    public function setConfirmationMethod(?string $confirmationMethod): self
    {
        $this->confirmationMethod = $confirmationMethod;

        return $this;
    }

    public function getCreated(): ?string
    {
        return $this->created;
    }

    public function setCreated(?string $created): self
    {
        $this->created = $created;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(?string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getCustomer(): ?string
    {
        return $this->customer;
    }

    public function setCustomer(?string $customer): self
    {
        $this->customer = $customer;

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

    public function getInvoice(): ?string
    {
        return $this->invoice;
    }

    public function setInvoice(?string $invoice): self
    {
        $this->invoice = $invoice;

        return $this;
    }

    /**
     * @return Collection<int, Order>
     */
    public function getCommande(): Collection
    {
        return $this->commande;
    }

    public function addCommande(Order $commande): self
    {
        if (!$this->commande->contains($commande)) {
            $this->commande[] = $commande;
            $commande->setStripeTransaction($this);
        }

        return $this;
    }

    public function removeCommande(Order $commande): self
    {
        if ($this->commande->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getStripeTransaction() === $this) {
                $commande->setStripeTransaction(null);
            }
        }

        return $this;
    }
}
