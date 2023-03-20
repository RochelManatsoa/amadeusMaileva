<?php

namespace App\Manager;

use Twig\Environment as Twig;
use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Recipient};

class RecipientManager
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
        $recipient = new Recipient();

        return $recipient;
    }

    public function save(Recipient $recipient)
    {
        $this->em->persist($recipient);
        $this->em->flush();
    }
}
