<?php

namespace App\Manager;

use Twig\Environment as Twig;
use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Envoi, Resiliation, StripeTransaction};
use DateTime;

class StripeTransactionManager
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
        $stripeTransaction = new StripeTransaction();

        return $stripeTransaction;
    }

    public function save(StripeTransaction $stripeTransaction)
    {
        $this->em->persist($stripeTransaction);
        $this->em->flush();
    }
}
