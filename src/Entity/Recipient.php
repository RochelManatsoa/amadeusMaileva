<?php

namespace App\Entity;

use App\Repository\RecipientRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RecipientRepository::class)
 */
class Recipient
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
    private $recipientId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryCode;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLine1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine3;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLine4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine5;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $addressLine6;

    /**
     * @ORM\Column(type="json")
     */
    private $documentsOverride = [];

    /**
     * @ORM\ManyToOne(targetEntity=Envoi::class, inversedBy="recipients")
     * @ORM\JoinColumn(nullable=false)
     */
    private $send;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRecipientId(): ?string
    {
        return $this->recipientId;
    }

    public function setRecipientId(string $recipientId): self
    {
        $this->recipientId = $recipientId;

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function setCountryCode(string $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(string $addressLine1): self
    {
        $this->addressLine1 = $addressLine1;

        return $this;
    }

    public function getAddressLine2(): ?string
    {
        return $this->addressLine2;
    }

    public function setAddressLine2(?string $addressLine2): self
    {
        $this->addressLine2 = $addressLine2;

        return $this;
    }

    public function getAddressLine3(): ?string
    {
        return $this->addressLine3;
    }

    public function setAddressLine3(?string $addressLine3): self
    {
        $this->addressLine3 = $addressLine3;

        return $this;
    }

    public function getAddressLine4(): ?string
    {
        return $this->addressLine4;
    }

    public function setAddressLine4(string $addressLine4): self
    {
        $this->addressLine4 = $addressLine4;

        return $this;
    }

    public function getAddressLine5(): ?string
    {
        return $this->addressLine5;
    }

    public function setAddressLine5(?string $addressLine5): self
    {
        $this->addressLine5 = $addressLine5;

        return $this;
    }

    public function getAddressLine6(): ?string
    {
        return $this->addressLine6;
    }

    public function setAddressLine6(string $addressLine6): self
    {
        $this->addressLine6 = $addressLine6;

        return $this;
    }

    public function getDocumentsOverride(): ?array
    {
        return $this->documentsOverride;
    }

    public function setDocumentsOverride(array $documentsOverride): self
    {
        $this->documentsOverride = $documentsOverride;

        return $this;
    }

    public function getSend(): ?Envoi
    {
        return $this->send;
    }

    public function setSend(?Envoi $send): self
    {
        $this->send = $send;

        return $this;
    }
}
