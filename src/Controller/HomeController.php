<?php

namespace App\Controller;

use App\Services\Maileva\MailevaApi;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(
        MailevaApi $mailevaApi,
        CategoryRepository $categoryRepository
    ): Response {
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/comment-ca-marche", name="app_how_it_work")
     */
    public function howItWorks(MailevaApi $token): Response
    {
        return $this->render('home/howItWorks.html.twig', []);
    }

    /**
     * @Route("/tutoriels-resiliation-facile", name="app_resiliation_facile")
     */
    public function resiliationFacile(MailevaApi $token): Response
    {
        return $this->render('home/resiliationFacile.html.twig', []);
    }

}
