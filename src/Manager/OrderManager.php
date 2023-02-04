<?php

namespace App\Manager;

use Twig\Environment as Twig;
use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Envoi, Resiliation, Order};
use DateTime;

class OrderManager
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
        $order = new Order();
        $order->setCreatedAt(new DateTime());

        return $order;
    }

    public function save(Order $order)
    {
        $this->em->persist($order);
        $this->em->flush();
    }
}
