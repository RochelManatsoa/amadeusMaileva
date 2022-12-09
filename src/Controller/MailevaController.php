<?php

namespace App\Controller;

use App\Entity\Envoi;
use App\Entity\Resiliation;
use App\Manager\EnvoiManager;
use App\Services\Maileva\MailevaApi;
use Symfony\Component\HttpFoundation\Response;
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
        EnvoiManager $envoiManager
        )
    {
        // dd($mailevaApi->getAllSendings());
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
        // dump($response);
        $envoi->setEnvoiId($response->id);
        $envoi->setStatus($response->status);
        $envoi->setDocumentCount(0);
        $envoiManager->save($envoi);
        $submitResponse = $mailevaApi->submitSending($envoi);
        if(isset($submitResponse->errors[0])){
            dd($submitResponse->errors[0]);
        }
        dd("envoyé");

        return $this->render('maileva/recap.html.twig', [
            'category' => $resiliation->getService()->getCategory()->getName(),
            'resiliation' => $resiliation,
        ]);
    }
}
