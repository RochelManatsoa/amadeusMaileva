<?php

namespace App\Manager;

use Twig\Environment as Twig;
use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Envoi, Resiliation, Document};

class DocumentManager
{
    public function __construct(
        EntityManagerInterface $em,
        MailevaApi $mailevaApi,
        Twig $twig
    )
    {
        $this->em = $em;
        $this->mailevaApi = $mailevaApi;
        $this->twig = $twig;
    }

    public function init()
    {
        $document = new Document();

        return $document;
    }

    public function save(Document $document)
    {
        $this->em->persist($document);
        $this->em->flush();
    }
}
