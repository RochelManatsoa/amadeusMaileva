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
        // dd($mailevaApi->getOneSending('5fc2c115-7087-4336-ad6f-c76bc61041fb'));
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories,
            'menu' => 'accueil',
        ]);
    }

    /**
     * @Route("/home2", name="app_home2")
     */
    public function index2(
        MailevaApi $mailevaApi,
        CategoryRepository $categoryRepository
    ): Response {
        // dd($mailevaApi->getOneSending('5fc2c115-7087-4336-ad6f-c76bc61041fb'));
        $categories = $categoryRepository->findAll();

        return $this->render('home/index2.html.twig', [
            'categories' => $categories,
            'menu' => 'accueil',
            'template' => 'design2',
        ]);
    }

    /**
     * @Route("/comment-ca-marche", name="app_how_it_work")
     */
    public function howItWorks(MailevaApi $token): Response
    {
        return $this->render('home/howItWorks.html.twig', [
            'menu' => 'howItWorks',
        ]);
    }

    /**
     * @Route("/tutoriels-resiliation-facile", name="app_resiliation_facile")
     */
    public function resiliationFacile(MailevaApi $token): Response
    {
        return $this->render('home/resiliationFacile.html.twig', [
            'menu' => 'resiliationFacile',
        ]);
    }
}
