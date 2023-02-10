<?php

namespace App\Services\Restpdf;

use Doctrine\ORM\EntityManagerInterface;

class RestpdfApi
{


    public function __construct(
        $endpoint,
        $apiKey,
        EntityManagerInterface $em
    ) {
        $this->endpoint = $endpoint;
        $this->apiKey = $apiKey;
        $this->em = $em;
    }

    public function generatePdf(string $file, $html)
    {

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->endpoint,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_HTTPHEADER     => [
                "X-API-KEY: {$this->apiKey}",
                'Content-Type: application/json'
            ],
            CURLOPT_POSTFIELDS     => json_encode([
                "output" => "data",
                "html"    => $html,
                "asdasd" => "A4",
                // "PdfOptions" => [
                //     "page_header" => 
                // ]
            ])
        ]);

        $curlResponse = curl_exec($curl);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) == 200) {
            file_put_contents($file, $curlResponse);
        } else {
            echo 'There was a problem converting the URL to PDF';
        }

        curl_close($curl);
    }
}
