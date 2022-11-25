<?php

namespace App\Controller;

use DateTime;
use App\Entity\Letter;
use App\Form\ServiceType;
use App\Form\ServiceFormType;
use App\Form\ResiliationFormType;
use App\Repository\LetterRepository;
use App\Services\Maileva\MailevaApi;
use App\Repository\ServiceRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\{Service, Category, Resiliation};
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
        ServiceRepository $serviceRepository
    ): Response {
        $services = $serviceRepository->findAll($category);
        $dataServices = $this->objectToArray($services);
        // dd($dataServices);
        $services = $serviceRepository->findAll($category);
        $models = $this->objectToArray($letterRepository->findAll());
        $resiliation = new Resiliation();
        $resiliation->setCreatedAt(new \DateTimeImmutable());
        $form = $this->createForm(ResiliationFormType::class, $resiliation);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
            // "name": "Résiliation d'un abonnement téléphonique",
            // "custom_id": "order_1234",
            // "custom_data": "order_1234",
            // "acknowledgement_of_receipt": true,
            // "acknowledgement_of_receipt_scanning": true,
            // "color_printing": true,
            // "duplex_printing": true,
            // "optional_address_sheet": false,
            // "notification_email": "do_not_reply@maileva.com",
            // "sender_address_line_1": "Société Durand",
            // "sender_address_line_2": "M. Pierre DUPONT",
            // "sender_address_line_3": "Batiment B",
            // "sender_address_line_4": "10 avenue Charles de Gaulle",
            // "sender_address_line_5": "",
            // "sender_address_line_6": "94673 Charenton-Le-Pont",
            // "sender_country_code": "FR",
            // "archiving_duration": 3,
            // "return_envelope_reference": 123456
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
     * @Route("/apercu-pdf/{slug}", name="app_doc_preview")
     */
    public function preview(Resiliation $resiliation)
    {

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
