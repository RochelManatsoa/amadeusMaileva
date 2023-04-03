<?php

namespace App\Controller;

use App\Entity\Resiliation;
use App\Manager\{DocumentManager, RecipientManager};
use App\Manager\EnvoiManager;
use App\Manager\ResiliationManager;
use App\Services\Maileva\MailevaApi;
use App\Services\Restpdf\RestpdfApi;
use stdClass;
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
        RecipientManager $recipientManager,
        DocumentManager $documentManager
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

        // Création d'un envoi

        $response = $mailevaApi->postSending($params);
        if(isset($response->errors[0])){
            dd($response->errors[0]);
        }

        // save envoi
        $envoi->setEnvoiId($response->id);
        $envoi->setStatus($response->status);
        $envoi->setDocumentCount(0);
        $envoiManager->save($envoi);

        // Ajout d'un document à l'envoi.

        $resiliationManager->generateResiliation($resiliation, $restpdfApi);
        // $resiliationManager->generateSnappyResiliation($resiliation);
        $doc = $mailevaApi->addDocSending($envoi, $resiliation);
        if(isset($doc->errors[0])){
            dd($doc->errors[0]);
        }

        // mis à jour page_count du document
        $docResponse = $mailevaApi->getDocSending($doc->id, $envoi->getEnvoiId());

        // save document
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

        $envoi->setDocumentCount(1);
        $envoiManager->save($envoi);

        // Ajout d'un destinataire à l'envoi

        $service = $resiliation->getService();
        $recipient = [
            "custom_id" => $service->getSlug(),
            "address_line_1" => $service->getName(),
            "address_line_2" => "",
            "address_line_3" => "",
            "address_line_4" => $service->getAddress(),
            "address_line_5" => $service->getComplement(),
            "address_line_6" => $service->getZipCode().' '.$service->getCity(),
            "country_code" => "FR",
        ];
        $pageRange = new stdClass();
        $pageRange->document_id = $document->getDocId();
        $pageRange->start_page = "1";
        $pageRange->end_page = (string)$document->getPagesCount() === "0" ? "1" : (string)$document->getPagesCount();

        $recipient['documents_override'] = [
            $pageRange
        ];

        $responseRecipient = $mailevaApi->addRecipientSending($recipient, $envoi);
        if(isset($responseRecipient->errors[0])){
            dd($responseRecipient);
        }

        // save recipient
        $recipient = $recipientManager->init();
        $recipient->setRecipientId($responseRecipient->id);
        $recipient->setCustomId($responseRecipient->custom_id);
        $recipient->setStatus($responseRecipient->status);
        $recipient->setCountryCode($responseRecipient->country_code);
        $recipient->setPagesRange(json_encode($responseRecipient->documents_override));
        $recipient->setAddressLine1($responseRecipient->address_line_1);
        $recipient->setAddressLine2($responseRecipient->address_line_2);
        $recipient->setAddressLine3($responseRecipient->address_line_3);
        $recipient->setAddressLine4($responseRecipient->address_line_4);
        // $recipient->setAddressLine5($responseRecipient->address_line_5);
        $recipient->setAddressLine6($responseRecipient->address_line_6);
        $recipient->setSend($envoi);
        $recipientManager->save($recipient);

        // Finalisation d'un envoi

        $submitResponse = $mailevaApi->submitSending($envoi);
        if(isset($submitResponse->errors[0])){
            dd($submitResponse->errors[0]);
        }

        return $this->redirectToRoute('app_stripe_payment', [
            'customId' => $resiliation->getCustomId(),
        ]);
    }
}
