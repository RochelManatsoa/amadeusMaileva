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
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $addressLine6;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $countryCode;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $pagesRange;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusDetail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $lastDeliveryStatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastDeliveryStatusDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $postagePrice;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $postageClass;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $registredNumber;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $archiveDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $archiveUrl;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $acknowledgementOfReceiptArchiveDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acknowledgementOfReceiptUrl;

    /**
     * @ORM\ManyToOne(targetEntity=Envoi::class, inversedBy="recipients")
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

    public function getAddressLine1(): ?string
    {
        return $this->addressLine1;
    }

    public function setAddressLine1(?string $addressLine1): self
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

    public function setAddressLine4(?string $addressLine4): self
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

    public function setAddressLine6(?string $addressLine6): self
    {
        $this->addressLine6 = $addressLine6;

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

    public function getPagesRange(): ?string
    {
        return $this->pagesRange;
    }

    public function setPagesRange(?string $pagesRange): self
    {
        $this->pagesRange = $pagesRange;

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

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(?string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    public function getStatusDetail(): ?string
    {
        return $this->statusDetail;
    }

    public function setStatusDetail(?string $statusDetail): self
    {
        $this->statusDetail = $statusDetail;

        return $this;
    }

    public function getLastDeliveryStatus(): ?string
    {
        return $this->lastDeliveryStatus;
    }

    public function setLastDeliveryStatus(?string $lastDeliveryStatus): self
    {
        $this->lastDeliveryStatus = $lastDeliveryStatus;

        return $this;
    }

    public function getLastDeliveryStatusDate(): ?\DateTimeInterface
    {
        return $this->lastDeliveryStatusDate;
    }

    public function setLastDeliveryStatusDate(?\DateTimeInterface $lastDeliveryStatusDate): self
    {
        $this->lastDeliveryStatusDate = $lastDeliveryStatusDate;

        return $this;
    }

    public function getPostagePrice(): ?int
    {
        return $this->postagePrice;
    }

    public function setPostagePrice(?int $postagePrice): self
    {
        $this->postagePrice = $postagePrice;

        return $this;
    }

    public function getPostageClass(): ?string
    {
        return $this->postageClass;
    }

    public function setPostageClass(?string $postageClass): self
    {
        $this->postageClass = $postageClass;

        return $this;
    }

    public function getRegistredNumber(): ?string
    {
        return $this->registredNumber;
    }

    public function setRegistredNumber(?string $registredNumber): self
    {
        $this->registredNumber = $registredNumber;

        return $this;
    }

    public function getArchiveDate(): ?\DateTimeInterface
    {
        return $this->archiveDate;
    }

    public function setArchiveDate(?\DateTimeInterface $archiveDate): self
    {
        $this->archiveDate = $archiveDate;

        return $this;
    }

    public function getArchiveUrl(): ?string
    {
        return $this->archiveUrl;
    }

    public function setArchiveUrl(?string $archiveUrl): self
    {
        $this->archiveUrl = $archiveUrl;

        return $this;
    }

    public function getAcknowledgementOfReceiptArchiveDate(): ?\DateTimeInterface
    {
        return $this->acknowledgementOfReceiptArchiveDate;
    }

    public function setAcknowledgementOfReceiptArchiveDate(?\DateTimeInterface $acknowledgementOfReceiptArchiveDate): self
    {
        $this->acknowledgementOfReceiptArchiveDate = $acknowledgementOfReceiptArchiveDate;

        return $this;
    }

    public function getAcknowledgementOfReceiptUrl(): ?string
    {
        return $this->acknowledgementOfReceiptUrl;
    }

    public function setAcknowledgementOfReceiptUrl(?string $acknowledgementOfReceiptUrl): self
    {
        $this->acknowledgementOfReceiptUrl = $acknowledgementOfReceiptUrl;

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
