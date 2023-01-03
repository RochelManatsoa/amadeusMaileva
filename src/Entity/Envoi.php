<?php

namespace App\Entity;

use App\Repository\EnvoiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EnvoiRepository::class)
 */
class Envoi
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
    private $name;

    /**
     * @ORM\Column(type="string")
     */
    private $customId;

    /**
     * @ORM\Column(type="string")
     */
    private $envoiId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $customData;

    /**
     * @ORM\Column(type="boolean")
     */
    private $acknowledgementOfReceipt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $acknowledgementOfReceiptScanning;

    /**
     * @ORM\Column(type="boolean")
     */
    private $colorPrinting;

    /**
     * @ORM\Column(type="boolean")
     */
    private $duplexPrinting;

    /**
     * @ORM\Column(type="boolean")
     */
    private $optionnalAddressSheet;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $notificationEmail;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sendersAddressLine1;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sendersAddressLine2;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sendersAddressLine3;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sendersAddressLine4;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sendersAddressLine5;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $sendersAddressLine6;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $senderCountryCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $archivingDuration;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $returnEnvelopeReference;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusDetail;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $documentCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $documentWeight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pageCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $billetPagesCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sheetCount;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $creationDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $submissionDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $scheduledDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $processedDate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $archiveDate;

    /**
     * @ORM\OneToMany(targetEntity=Document::class, mappedBy="send")
     */
    private $documents;

    public function __construct()
    {
        $this->documents = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getEnvoiId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isAcknowledgementOfReceipt(): ?bool
    {
        return $this->acknowledgementOfReceipt;
    }

    public function setAcknowledgementOfReceipt(bool $acknowledgementOfReceipt): self
    {
        $this->acknowledgementOfReceipt = $acknowledgementOfReceipt;

        return $this;
    }

    public function isAcknowledgementOfReceiptScanning(): ?bool
    {
        return $this->acknowledgementOfReceiptScanning;
    }

    public function setAcknowledgementOfReceiptScanning(bool $acknowledgementOfReceiptScanning): self
    {
        $this->acknowledgementOfReceiptScanning = $acknowledgementOfReceiptScanning;

        return $this;
    }

    public function isColorPrinting(): ?bool
    {
        return $this->colorPrinting;
    }

    public function setColorPrinting(bool $colorPrinting): self
    {
        $this->colorPrinting = $colorPrinting;

        return $this;
    }

    public function isDuplexPrinting(): ?bool
    {
        return $this->duplexPrinting;
    }

    public function setDuplexPrinting(bool $duplexPrinting): self
    {
        $this->duplexPrinting = $duplexPrinting;

        return $this;
    }

    public function isOptionnalAddressSheet(): ?bool
    {
        return $this->optionnalAddressSheet;
    }

    public function setOptionnalAddressSheet(bool $optionnalAddressSheet): self
    {
        $this->optionnalAddressSheet = $optionnalAddressSheet;

        return $this;
    }

    public function getNotificationEmail(): ?string
    {
        return $this->notificationEmail;
    }

    public function setNotificationEmail(string $notificationEmail): self
    {
        $this->notificationEmail = $notificationEmail;

        return $this;
    }

    public function getSendersAddressLine1(): ?string
    {
        return $this->sendersAddressLine1;
    }

    public function setSendersAddressLine1(?string $sendersAddressLine1): self
    {
        $this->sendersAddressLine1 = $sendersAddressLine1;

        return $this;
    }

    public function getSendersAddressLine2(): ?string
    {
        return $this->sendersAddressLine2;
    }

    public function setSendersAddressLine2(?string $sendersAddressLine2): self
    {
        $this->sendersAddressLine2 = $sendersAddressLine2;

        return $this;
    }

    public function getSendersAddressLine3(): ?string
    {
        return $this->sendersAddressLine3;
    }

    public function setSendersAddressLine3(?string $sendersAddressLine3): self
    {
        $this->sendersAddressLine3 = $sendersAddressLine3;

        return $this;
    }

    public function getSendersAddressLine4(): ?string
    {
        return $this->sendersAddressLine4;
    }

    public function setSendersAddressLine4(?string $sendersAddressLine4): self
    {
        $this->sendersAddressLine4 = $sendersAddressLine4;

        return $this;
    }

    public function getSendersAddressLine5(): ?string
    {
        return $this->sendersAddressLine5;
    }

    public function setSendersAddressLine5(?string $sendersAddressLine5): self
    {
        $this->sendersAddressLine5 = $sendersAddressLine5;

        return $this;
    }

    public function getSendersAddressLine6(): ?string
    {
        return $this->sendersAddressLine6;
    }

    public function setSendersAddressLine6(?string $sendersAddressLine6): self
    {
        $this->sendersAddressLine6 = $sendersAddressLine6;

        return $this;
    }

    public function getSenderCountryCode(): ?string
    {
        return $this->senderCountryCode;
    }

    public function setSenderCountryCode(?string $senderCountryCode): self
    {
        $this->senderCountryCode = $senderCountryCode;

        return $this;
    }

    public function getArchivingDuration(): ?int
    {
        return $this->archivingDuration;
    }

    public function setArchivingDuration(?int $archivingDuration): self
    {
        $this->archivingDuration = $archivingDuration;

        return $this;
    }

    public function getReturnEnvelopeReference(): ?string
    {
        return $this->returnEnvelopeReference;
    }

    public function setReturnEnvelopeReference(?string $returnEnvelopeReference): self
    {
        $this->returnEnvelopeReference = $returnEnvelopeReference;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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

    public function getDocumentCount(): ?int
    {
        return $this->documentCount;
    }

    public function setDocumentCount(?int $documentCount): self
    {
        $this->documentCount = $documentCount;

        return $this;
    }

    public function getDocumentWeight(): ?int
    {
        return $this->documentWeight;
    }

    public function setDocumentWeight(?int $documentWeight): self
    {
        $this->documentWeight = $documentWeight;

        return $this;
    }

    public function getPageCount(): ?int
    {
        return $this->pageCount;
    }

    public function setPageCount(?int $pageCount): self
    {
        $this->pageCount = $pageCount;

        return $this;
    }

    public function getBilletPagesCount(): ?int
    {
        return $this->billetPagesCount;
    }

    public function setBilletPagesCount(?int $billetPagesCount): self
    {
        $this->billetPagesCount = $billetPagesCount;

        return $this;
    }

    public function getSheetCount(): ?int
    {
        return $this->sheetCount;
    }

    public function setSheetCount(?int $sheetCount): self
    {
        $this->sheetCount = $sheetCount;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(?\DateTimeInterface $creationDate): self
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->submissionDate;
    }

    public function setSubmissionDate(?\DateTimeInterface $submissionDate): self
    {
        $this->submissionDate = $submissionDate;

        return $this;
    }

    public function getScheduledDate(): ?\DateTimeInterface
    {
        return $this->scheduledDate;
    }

    public function setScheduledDate(?\DateTimeInterface $scheduledDate): self
    {
        $this->scheduledDate = $scheduledDate;

        return $this;
    }

    public function getProcessedDate(): ?\DateTimeInterface
    {
        return $this->processedDate;
    }

    public function setProcessedDate(?\DateTimeInterface $processedDate): self
    {
        $this->processedDate = $processedDate;

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

    public function getCustomId(): ?string
    {
        return $this->customId;
    }

    public function setCustomId(string $customId): self
    {
        $this->customId = $customId;

        return $this;
    }

    public function getCustomData(): ?string
    {
        return $this->customData;
    }

    public function setCustomData(?string $customData): self
    {
        $this->customData = $customData;

        return $this;
    }

    public function getEnvoiId(): ?string
    {
        return $this->envoiId;
    }

    public function setEnvoiId(string $envoiId): self
    {
        $this->envoiId = $envoiId;

        return $this;
    }

    /**
     * @return Collection<int, Document>
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Document $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setSend($this);
        }

        return $this;
    }

    public function removeDocument(Document $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getSend() === $this) {
                $document->setSend(null);
            }
        }

        return $this;
    }
}
