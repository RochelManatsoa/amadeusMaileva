<?php

namespace App\Services\Maileva;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


class MailevaApi
{

    public function __construct(
        $endpoint, 
        $type,
        $clientId, 
        $clientSecret,
        $clientUsername,
        $clientPassword, 
        LoggerInterface $mailevaLogger,
        TokenStorageInterface $tokenStorage, 
        EntityManagerInterface $em)
    {
        $this->endpoint = $endpoint;
        $this->type = $type;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->clientUsername = $clientUsername;
        $this->clientPassword = $clientPassword;
        $this->mailevaLogger = $mailevaLogger;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    private function connect(){
        
        $identifications = [
            'client_id'   => $this->clientId,
            'client_secret'   => $this->clientSecret,
            'grant_type'   => $this->type,
            'username' => $this->clientUsername,
            'password' => $this->clientPassword,
        ];

        $ch = curl_init($this->endpoint);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($identifications));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        return $response;
    }

    public function getToken(){
        return $this->connect();
    }

}