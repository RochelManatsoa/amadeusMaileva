<?php

namespace App\Manager;

use Knp\Snappy\Pdf;
use Twig\Environment as Twig;
use Symfony\Component\Uid\Ulid;
use App\Services\Maileva\MailevaApi;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\{Resiliation, Service, Client};

class ResiliationManager
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
        $resiliation = new Resiliation();
        $resiliation->setCreatedAt(new \DateTime());
        $resiliation->setCustomId(new Ulid());

        return $resiliation;
    }

    public function save(Resiliation $resiliation)
    {
		$this->em->persist($resiliation);
        $this->em->flush();
    }

    public function generatePreview(Resiliation $resiliation)
    {
		$folder = $resiliation->getGeneratedResiliationPath();
        $file = $resiliation->getGeneratedPreviewPathFile();
        if (!is_dir($folder)) mkdir($folder, 0777, true);
		$scanFolder = scandir($folder);
        // if (!in_array("preview.pdf", $scanFolder)) { 
            $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
            $html = $this->twig->render("resiliation/pdf/preview.pdf.twig", ['resiliation' => $resiliation]);
            $output = $snappy->getOutputFromHtml($html);
            file_put_contents($file, $output);
        // }
        
        return $file;
	}

    public function generateResiliation(Resiliation $resiliation)
    {
		$folder = $resiliation->getGeneratedResiliationPath();
        $file = $resiliation->getGeneratedResiliationPathFile();
        if (!is_dir($folder)) mkdir($folder, 0777, true);
		$scanFolder = scandir($folder);
        if (!in_array("resiliation.pdf", $scanFolder)) { 
            $snappy = new Pdf('/usr/local/bin/wkhtmltopdf');
            $html = $this->twig->render("resiliation/pdf/resiliation.pdf.twig", ['resiliation' => $resiliation]);
            $output = $snappy->getOutputFromHtml($html);
            file_put_contents($file, $output);
        }
        
        return $file;
	}
}
