<?php

namespace App\Controller;

use App\Entity\Resiliation;
use App\Manager\DocumentManager;
use App\Manager\EnvoiManager;
use App\Manager\RecipientManager;
use App\Manager\ResiliationManager;
use App\Services\Maileva\MailevaApi;
use App\Services\Restpdf\RestpdfApi;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MailevaController extends AbstractController
{
    /**
     * @Route("/send/{customId}", name="app_maileva_send")
     */
    public function send(
        Resiliation $resiliation, 
        MailevaApi $mailevaApi, 
        RestpdfApi $restpdfApi,
        EnvoiManager $envoiManager,
        ResiliationManager $resiliationManager,
        DocumentManager $documentManager,
        RecipientManager $recipientManager
        )
    {
        $envoi = $envoiManager->setResiliation($resiliation);        
        $params = [
            "name" => $envoi->getName(),
            "custom_id" => $envoi->getCustomId(),
            "custom_data" => $envoi->getCustomData(),
            "acknowledgement_of_receipt" => $envoi->isAcknowledgementOfReceipt(),
            "acknowledgement_of_receipt_scanning" => $envoi->isAcknowledgementOfReceiptScanning(),
            "color_printing" => $envoi->isColorPrinting(),
            "duplex_printing" => $envoi->isDuplexPrinting(),
            "optional_address_sheet" => $envoi->isOptionnalAddressSheet(),
            "notification_email" => $envoi->getNotificationEmail(),
            "sender_address_line_1" => $envoi->getSendersAddressLine1(),
            "sender_address_line_2" => $envoi->getSendersAddressLine2(),
            "sender_address_line_3" => $envoi->getSendersAddressLine3(),
            "sender_address_line_4" => $envoi->getSendersAddressLine4(),
            "sender_address_line_5" => $envoi->getSendersAddressLine5(),
            "sender_address_line_6" => $envoi->getSendersAddressLine6(),
            "sender_country_code" => $envoi->getSenderCountryCode(),
            "archiving_duration" => $envoi->getArchivingDuration(),
        ];

        $response = $mailevaApi->postSending($params);
        if(isset($response->errors[0])){
            dd($response->errors[0]);
        }
        $envoi->setEnvoiId($response->id);
        $envoi->setStatus($response->status);
        $envoi->setDocumentCount(0);
        $envoiManager->save($envoi);
        
        $resiliationManager->generateResiliation($resiliation, $restpdfApi);
        
        $docResponse = $mailevaApi->addDocSending($envoi, $resiliation);
        if(isset($docResponse->errors[0])){
            dd($docResponse->errors[0]);
        }
        $document = $documentManager->init();
        $document->setDocId($docResponse->id);
        $document->setPriority($docResponse->priority);
        $document->setName($docResponse->name);
        $document->setType($docResponse->type);
        $document->setPagesCount($docResponse->pages_count);
        $document->setSheetsCount($docResponse->sheets_count);
        $document->setSize($docResponse->size);
        $document->setConvertedSize($docResponse->converted_size);
        $document->setSend($envoi);
        $documentManager->save($document);
        
        $recipientResponse = $mailevaApi->addRecipient($envoi, $resiliation, $docResponse->id);
        if(isset($recipientResponse->errors[0])){
            dd($recipientResponse->errors[0]);
        }
        $recipient = $recipientManager->init();
        $recipient->setRecipientId($recipientResponse->id);
        $recipient->setCustomId($recipientResponse->custom_id);
        $recipient->setStatus($recipientResponse->status);
        $recipient->setCountryCode($recipientResponse->country_code);
        $recipient->setAddressLine1($recipientResponse->address_line_1);
        $recipient->setAddressLine2($recipientResponse->address_line_2);
        $recipient->setAddressLine3($recipientResponse->address_line_3);
        $recipient->setAddressLine4($recipientResponse->address_line_4);
        $recipient->setAddressLine5($recipientResponse->address_line_5);
        $recipient->setAddressLine6($recipientResponse->address_line_6);
        $recipient->setDocumentsOverride($recipientResponse->documents_override);
        $recipient->setSend($envoi);
        $recipientManager->save($recipient);

        $submitResponse = $mailevaApi->submitSending($envoi);
        if(isset($submitResponse->errors[0])){
            dd($submitResponse->errors[0]);
        }

        return $this->redirectToRoute('app_stripe_payment', [
            'customId' => $resiliation->getCustomId(),
        ]);
    }
}
