<?php

namespace App\Controller;

use App\Form\ServiceType;
use App\Form\ServiceFormType;
use App\Entity\{Service, Category, Resiliation};
use App\Form\ResiliationFormType;
use App\Services\Maileva\MailevaApi;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(MailevaApi $token, CategoryRepository $categoryRepository): Response
    {
        // dd(json_decode($token->getToken()));
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/resiliation/{slug}", name="app_home_category")
     */
    public function category(Category $category, ServiceRepository $serviceRepository): Response
    {
        $services = $serviceRepository->findAll($category);
        $resiliation = new Resiliation();
        $form = $this->createForm(ResiliationFormType::class, $resiliation);

        return $this->render('home/service.html.twig', [
            'services' => $services,
            'category' => $category->getName(),
            'form' => $form->createView()
        ]);
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
