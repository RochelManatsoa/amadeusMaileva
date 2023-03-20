<?php

namespace App\Services\Maileva;

use App\Entity\{ApiExchange, Envoi, Resiliation};
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use App\Repository\{DocumentRepository, RecipientRepository};

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
        DocumentRepository $documentRepository,
        RecipientRepository $recipientRepository,
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
        $this->documentRepository = $documentRepository;
        $this->recipientRepository = $recipientRepository;
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
        // $user = $this->tokenStorage->getToken()->getUser();
        // if ($user instanceof User) {
        //     $email = $user->getEmail();
        // } else {
        //     $email = 'anonymous';
        // }
        // return $email;
        return 'annonymous';
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
        $this->log("parameters postSending", \json_encode($params, JSON_PRETTY_PRINT));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode($params, JSON_PRETTY_PRINT));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());
        
        $ch = curl_init($this->endpointApi . '/sendings');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_POSTFIELDS, \json_encode($params));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        $this->log("response postSending", \json_encode($response, JSON_PRETTY_PRINT), true);
        $this->saveExchange($apiExchange, \json_encode($response, JSON_PRETTY_PRINT));

        return $response;
    }

    public function submitSending(Envoi $envoi)
    {

        $token = $this->connect();
        $this->log("parameters submitSending", \json_encode($this->objectToArray($envoi), JSON_PRETTY_PRINT));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode($this->objectToArray($envoi), JSON_PRETTY_PRINT));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());
        
        $ch = curl_init($this->endpointApi . '/sendings/'. $envoi->getEnvoiId());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        if(null === $response){
            $response = [
                'status' => 200,
                'message' => 'success'
            ];
        }
        $this->log("response submitSending", \json_encode($response, JSON_PRETTY_PRINT), true);
        $this->saveExchange($apiExchange, \json_encode($response, JSON_PRETTY_PRINT));

        return $response;
    }

    public function addDocSending(Envoi $envoi, Resiliation $resiliation)
    {

        $token = $this->connect();
        $this->log("parameters addDocSending", \json_encode($this->objectToArray($envoi), JSON_PRETTY_PRINT));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode($this->objectToArray($envoi), JSON_PRETTY_PRINT));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());
        
        $ch = curl_init($this->endpointApi . '/sendings/'. $envoi->getEnvoiId().'/documents');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data', $authorization));
        $fields = [
            'document' => curl_file_create($resiliation->getGeneratedResiliationPath().'/resiliation.pdf', 'application/pdf', 'resiliation.pdf'),
            'metadata' => \json_encode([
                'priority' => 1,
                'name' => 'lettre_resiliation.pdf',
            ]),
        ];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        $this->log("response addDocSending", \json_encode($response, JSON_PRETTY_PRINT), true);
        $this->saveExchange($apiExchange, \json_encode($response, JSON_PRETTY_PRINT));

        return $response;
    }

    public function addRecipientSending(array $recipient, Envoi $envoi){

        $token = $this->connect();
        $this->log("parameters addRecipientSending", \json_encode($recipient, JSON_PRETTY_PRINT));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode($recipient, JSON_PRETTY_PRINT));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());
        $ch = curl_init($this->endpointApi . '/sendings/'. $envoi->getEnvoiId().'/recipients');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_POSTFIELDS, \json_encode($recipient, JSON_PRETTY_PRINT));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        $this->log("response addRecipientSending", \json_encode($response, JSON_PRETTY_PRINT), true);
        $this->saveExchange($apiExchange, \json_encode($response, JSON_PRETTY_PRINT));

        return $response;

    }

    public function getDocSending($doc, $sendingId){

        $token = $this->connect();
        $this->log("parameters envoi", \json_encode(['document_id' => $doc, 'sending_id' => $sendingId], JSON_PRETTY_PRINT));
        $type = __FUNCTION__;
        $apiExchange = $this->initExchange($type, \json_encode(['document_id' => $doc, 'sending_id' => $sendingId], JSON_PRETTY_PRINT));
        $authorization = 'Authorization: Bearer ' . $token;

        $this->log("connet to MailevaApi ... ", '...');
        $this->log("user infos", $this->getUserInfos());

        $ch = curl_init($this->endpointApi . '/sendings/'. $sendingId.'/documents/'.$doc);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $authorization));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = json_decode(curl_exec($ch));
        $this->log("response $doc, $sendingId", \json_encode($response, JSON_PRETTY_PRINT), true);
        $this->saveExchange($apiExchange, \json_encode($response, JSON_PRETTY_PRINT));

        return $response;

    }

    private function objectToArray(Envoi $envoi){

        $recipients = $this->recipientRepository->findByEnvoi($envoi->getId());
        $documents = $this->documentRepository->findByEnvoi($envoi->getId());
        $recipientsArray = [];
        $documentsArray = [];

        foreach($recipients as $key => $value){
            $recipientsArray[$key] = [
                'recipientId' => $value->getRecipientId(),
                'addressLine1' => $value->getAddressLine1(),
                'addressLine4' => $value->getAddressLine4(),
                'addressLine6' => $value->getAddressLine6(),
                'documents_override' => $value->getPagesRange(),
                'status' => $value->getStatus(),
                'customId' => $value->getCustomId(),
            ];
        }

        foreach($documents as $key => $value){
            $documentsArray[$key] = [
                'docId' => $value->getDocId(),
                'priority' => $value->getPriority(),
                'name' => $value->getName(),
                'type' => $value->getType(),
                'pagesCount' => $value->getPagesCount(),
                'sheetsCount' => $value->getSheetsCount(),
                'size' => $value->getSize(),
                'convertedSize' => $value->getConvertedSize(),
            ];
        }

        $newArray = [];
        $newArray['name'] = $envoi->getName();
        $newArray['envoiId'] = $envoi->getEnvoiId();
        $newArray['customId'] = $envoi->getCustomId();
        $newArray['documentCount'] = $envoi->getDocumentCount();
        $newArray['documents'] = $documentsArray;
        $newArray['status'] = $envoi->getStatus();
        $newArray['client'] = $envoi->getSendersAddressLine1();
        $newArray['adresse'] = $envoi->getSendersAddressLine4().' '. $envoi->getSendersAddressLine6();
        $newArray['destinataire'] = $recipientsArray;

        return $newArray;
    }
}
