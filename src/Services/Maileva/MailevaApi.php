<?php

namespace App\Services\Maileva;

use App\Entity\ApiExchange;
use App\Entity\Envoi;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;


class MailevaApi
{

    public function __construct(
        $endpoint,
        $endpointApi,
        $type,
        $clientId,
        $clientSecret,
        $clientUsername,
        $clientPassword,
        LoggerInterface $mailevaLogger,
        TokenStorageInterface $tokenStorage,
        EntityManagerInterface $em
    ) {
        $this->endpoint = $endpoint;
        $this->endpointApi = $endpointApi;
        $this->type = $type;
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->clientUsername = $clientUsername;
        $this->clientPassword = $clientPassword;
        $this->mailevaLogger = $mailevaLogger;
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    private function connect()
    {

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

        $response = json_decode(curl_exec($ch));

        return $response->access_token;
    }

    private function initExchange(string $type, string $params) : ApiExchange
    {
        $apiExchange = new ApiExchange();
        $apiExchange
            ->setType($type)
            ->setParams($params);

        return $apiExchange;
    }

    private function saveExchange(ApiExchange $apiExchange, string $response): void
    {
        $apiExchange->setResponse($response);
        $apiExchange->setCreatedAt(new \DateTime());

        $this->em->persist($apiExchange);
        $this->em->flush();
    }

    private function log($key, $message, $end = false)
    {
        $this->mailevaLogger->info("=======================");
        $this->mailevaLogger->info($key . " : ");
        $this->mailevaLogger->info($message);
        if ($end) {
            $this->mailevaLogger->notice("=======================");
            $this->mailevaLogger->notice("=======================");
        }
    }

    private function getUserInfos()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof User) {
            $email = $user->getEmail();
        } else {
            $email = 'anonymous';
        }
        return $email;
        // return 'annonymous';
    }

    public function getAllSendings()
    {

        $token = $this->connect();
        $authorization = 'Authorization: Bearer ' . $token;

        $ch = curl_init($this->endpointApi . '/sendings');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));

        return $response;
    }

    public function getOneSending(string $sendingId)
    {

        $token = $this->connect();
        $authorization = 'Authorization: Bearer ' . $token;

        $ch = curl_init($this->endpointApi . '/sendings/' . $sendingId);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));

        return $response;
    }

    public function postSending(array $params)
    {

        $token = $this->connect();
        $this->log("parameters", \json_encode($params));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode($params));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());
        
        $ch = curl_init($this->endpointApi . '/sendings');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_POSTFIELDS, \json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        $this->log("response postSending", \json_encode($response), true);
        $this->saveExchange($apiExchange, \json_encode($response));

        return $response;
    }

    public function submitSending(Envoi $envoi)
    {

        $token = $this->connect();
        $this->log("parameters envoi", \json_encode($envoi));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode($envoi));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());
        
        $ch = curl_init($this->endpointApi . '/sendings/'. $envoi->getEnvoiId());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        $this->log("response submitSending", \json_encode($response), true);
        $this->saveExchange($apiExchange, \json_encode($response));

        return $response;
    }
}
