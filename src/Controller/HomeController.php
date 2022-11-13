<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Maileva\MailevaApi;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(MailevaApi $token): Response
    {
        // dd(json_decode($token->getToken()));
        return $this->render('home/index.html.twig', []);
    }

    /**
     * @Route("/comment-ca-marche", name="app_how_it_work")
     */
    public function commentCaMarche(MailevaApi $token): Response
    {
        return $this->render('home/commentCaMarche.html.twig', []);
    }

    /**
     * @Route("/tutoriels-resiliation-facile", name="app_resiliation_facile")
     */
    public function resiliationFacile(MailevaApi $token): Response
    {
        return $this->render('home/resiliationFacile.html.twig', []);
    }
}
