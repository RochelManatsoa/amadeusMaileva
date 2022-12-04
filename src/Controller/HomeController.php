<?php

namespace App\Controller;

use DateTime;
use App\Entity\Letter;
use App\Form\ServiceType;
use App\Form\ServiceFormType;
use App\Form\ResiliationFormType;
use App\Manager\ResiliationManager;
use App\Repository\LetterRepository;
use App\Services\Maileva\MailevaApi;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Service, Category, Resiliation};
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(MailevaApi $mailevaApi, CategoryRepository $categoryRepository): Response
    {
        // dd($mailevaApi->getOneSending('5fc2c115-7087-4336-ad6f-c76bc61041fb'));
        $categories = $categoryRepository->findAll();

        return $this->render('home/index.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/resiliation/{slug}", name="app_home_category")
     */
    public function category(
        Request $request,
        Category $category,
        LetterRepository $letterRepository,
        ServiceRepository $serviceRepository,
        ResiliationManager $resiliationManager
    ): Response {
        $service = $serviceRepository->find($category);
        $services = $serviceRepository->findAll($category);
        $dataServices = $this->objectToArray($services);
        $models = $this->objectToArray($letterRepository->findAll());
        $resiliation = $resiliationManager->init();
        $resiliation->setService($service);
        $form = $this->createForm(ResiliationFormType::class, $resiliation, ['defaultModel'=> null]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $resiliation = $form->getData();
            $resiliationManager->save($resiliation);

            return $this->redirectToRoute('app_preview', [
                'customId' => $resiliation->getCustomId(),
            ]);
        }

        return $this->render('home/service.html.twig', [
            'services' => $services,
            'dataServices' => $dataServices,
            'letters' => $models,
            'category' => $category->getName(),
            'form' => $form->createView()
        ]);
    }

    /**
     * Array of Object to array
     */
    private function objectToArray(array $array)
    {

        $newArray = [];

        foreach ($array as $key => $object) {

            if ($object instanceof Letter) {
                $newArray[$key + 1] = [
                    "content" => $object->getContent(),
                ];
            }

            if ($object instanceof Service) {
                $newArray[$object->getSlug()] = [
                    "address" => $object->getAddress(),
                    "complement" => $object->getComplement(),
                    "zipCode" => $object->getZipCode(),
                    "city" => $object->getCity(),
                    "name" => $object->getName(),
                ];
            }
        }

        return $newArray;
    }

    /**
     * @Route("/apercu-pdf/{customId}", name="app_doc_preview")
     */
    public function preview(Resiliation $resiliation, ResiliationManager $resiliationManager)
    {
        $file = $resiliationManager->generatePreview($resiliation);

        return new BinaryFileResponse($file);
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

    /**
     * @Route("/preview/{customId}", name="app_preview")
     */
    public function previewDocument(
        Resiliation $resiliation, 
        Request $request,
        LetterRepository $letterRepository,
        ServiceRepository $serviceRepository,
        ResiliationManager $resiliationManager
        )
    {
        $services = $serviceRepository->findAll($resiliation->getService()->getCategory());
        $dataServices = $this->objectToArray($services);
        $defaultModel = $letterRepository->findOneByName($resiliation->getType());
        $models = $this->objectToArray($letterRepository->findAll());
        $form = $this->createForm(ResiliationFormType::class, $resiliation, ['defaultModel'=>$defaultModel]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $resiliation = $form->getData();
            $resiliationManager->save($resiliation);

            return $this->redirectToRoute('app_preview', [
                'customId' => $resiliation->getCustomId(),
            ]);
        }

        return $this->render('home/service.html.twig', [
            'services' => $services,
            'dataServices' => $dataServices,
            'letters' => $models,
            'category' => $resiliation->getService()->getCategory()->getName(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/resume/{customId}", name="app_resume")
     */
    public function resume(Resiliation $resiliation)
    {
        return $this->render('home/recap.html.twig', [
            'category' => $resiliation->getService()->getCategory()->getName(),
            'resiliation' => $resiliation,
        ]);
    }
}
