<?php

namespace App\Manager;

use Twig\Environment as Twig;
use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Envoi, Resiliation};

class EnvoiManager
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
        $envoi = new Envoi();
        $envoi->setCreationDate(new \DateTime());

        return $envoi;
    }

    public function save(Envoi $envoi)
    {
        $this->em->persist($envoi);
        $this->em->flush();
    }

    public function setResiliation(Resiliation $resiliation)
    {
        $envoi = $this->init();
        $envoi->setName("Résiliation d'un abonnement téléphonique");
        $envoi->setCustomId($resiliation->getCustomId());
        $envoi->setCustomData(\json_encode($resiliation));
        $envoi->setAcknowledgementOfReceipt(true);
        $envoi->setAcknowledgementOfReceiptScanning(false);
        $envoi->setColorPrinting(true);
        $envoi->setDuplexPrinting(false);
        $envoi->setOptionnalAddressSheet(false);
        $envoi->setNotificationEmail("do_not_reply@maileva.com");
        $envoi->setSendersAddressLine1($this->getLine1($resiliation));
        $envoi->setSendersAddressLine2($this->getLine2($resiliation));
        $envoi->setSendersAddressLine3($this->getLine3($resiliation));
        $envoi->setSendersAddressLine4($this->getLine4($resiliation));
        $envoi->setSendersAddressLine5($this->getLine5($resiliation));
        $envoi->setSendersAddressLine6($this->getLine6($resiliation));
        $envoi->setSenderCountryCode('FR');
        $envoi->setArchivingDuration(3);
        $envoi->setReturnEnvelopeReference($this->getReference($resiliation));

        return $envoi;
    }


    private function getLine1($resiliation){
        if($resiliation instanceof Resiliation){
            $client = $resiliation->getClient();

            return $client->getLastName(). ' ' . $client->getFirstName();
        }
    }

    private function getLine2($resiliation){
        if($resiliation instanceof Resiliation){
            $client = $resiliation->getClient();

            return $client->getLastName(). ' ' . $client->getFirstName();
        }
    }

    private function getLine3($resiliation){
        if($resiliation instanceof Resiliation){
            $complement = $resiliation->getClient()->getAddress()->getComplement();

            return $complement;
        }
    }

    private function getLine4($resiliation){
        if($resiliation instanceof Resiliation){
            $address = $resiliation->getClient()->getAddress()->getName();

            return $address;
        }
    }

    private function getLine5($resiliation){
        if($resiliation instanceof Resiliation){
            $client = $resiliation->getClient();

            return "";
        }
    }

    private function getLine6($resiliation){
        if($resiliation instanceof Resiliation){
            $address = $resiliation->getClient()->getAddress();

            return $address->getZipCode().' '.$address->getCity();
        }
    }

    private function getReference($resiliation){
        if($resiliation instanceof Resiliation){
            $client = $resiliation->getClient();

            return $client->getLastName(). '' . $client->getFirstName();
        }
    }
}
