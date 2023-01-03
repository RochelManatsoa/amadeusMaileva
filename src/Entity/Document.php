<?php

namespace App\Entity;

use App\Repository\DocumentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=DocumentRepository::class)
 */
class Document
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
    private $docId;

    /**
     * @ORM\Column(type="integer")
     */
    private $priority;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $pagesCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $sheetsCount;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $size;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $convertedSize;

    /**
     * @ORM\ManyToOne(targetEntity=Envoi::class, inversedBy="documents")
     */
    private $send;

    public function __toString()
    {
        return $this->getDocId();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDocId(): ?string
    {
        return $this->docId;
    }

    public function setDocId(string $docId): self
    {
        $this->docId = $docId;

        return $this;
    }

    public function getPriority(): ?int
    {
        return $this->priority;
    }

    public function setPriority(int $priority): self
    {
        $this->priority = $priority;

        return $this;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getPagesCount(): ?int
    {
        return $this->pagesCount;
    }

    public function setPagesCount(?int $pagesCount): self
    {
        $this->pagesCount = $pagesCount;

        return $this;
    }

    public function getSheetsCount(): ?int
    {
        return $this->sheetsCount;
    }

    public function setSheetsCount(?int $sheetsCount): self
    {
        $this->sheetsCount = $sheetsCount;

        return $this;
    }

    public function getSize(): ?int
    {
        return $this->size;
    }

    public function setSize(?int $size): self
    {
        $this->size = $size;

        return $this;
    }

    public function getConvertedSize(): ?int
    {
        return $this->convertedSize;
    }

    public function setConvertedSize(?int $convertedSize): self
    {
        $this->convertedSize = $convertedSize;

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
