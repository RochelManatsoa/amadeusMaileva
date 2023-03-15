<?php

namespace App\Manager;

use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Envoi, Recipient};

class RecipientManager
{
    public function __construct(
        EntityManagerInterface $em,
        MailevaApi $mailevaApi
    )
    {
        $this->em = $em;
        $this->mailevaApi = $mailevaApi;
    }

    public function init()
    {
        $recipient = new Recipient();

        return $recipient;
    }

    public function save(Recipient $recipient)
    {
        $this->em->persist($recipient);
        $this->em->flush();
    }
}
