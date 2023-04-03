<?php

namespace App\Manager;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Client;

class ClientManager
{
    public function __construct(
        EntityManagerInterface $em
    )
    {
        $this->em = $em;
    }

    public function init()
    {
        $client = new Client();

        return $client;
    }

    public function save(Client $client)
    {
        $this->em->persist($client);
        $this->em->flush();
    }
}
