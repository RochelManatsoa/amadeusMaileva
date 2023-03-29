<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\Resiliation;
use App\Manager\ClientManager;
use App\Manager\ResiliationManager;
use App\Repository\ClientRepository;
use App\Repository\ResiliationRepository;
use App\Repository\ServiceRepository;
use stdClass;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Uid\Ulid;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/services", name="api_services", methods={"GET"})
     */
    public function index(ServiceRepository $serviceRepository): Response
    {
        return $this->json($serviceRepository->findAll(), 200, [], []);
    }

    /**
     * @Route("/api/resiliation/{customId}", name="api_resiliation", methods={"GET"})
     */
    public function getResiliation(ResiliationRepository $resiliationRepository, $customId): Response
    {
        $resiliation = $resiliationRepository->findOneByCustomId($customId);

        $resiliationService = $resiliation->getService();
        $resiliationClient = $resiliation->getClient();

        $service = new stdClass();
        $service->name = $resiliationService->getName();
        $service->slug = $resiliationService->getSlug();
        $service->address = $resiliationService->getAddress();
        $service->zipCode = $resiliationService->getZipCode();
        $service->city = $resiliationService->getCity();
        $service->name = $resiliationService->getName();
        $service->country = $resiliationService->getCountry();
        $service->category = $resiliationService->getCategory()->getName();

        $client = new stdClass();
        $client->firstName = $resiliationClient->getFirstName();
        $client->lastName = $resiliationClient->getLastName();
        $client->mobile = $resiliationClient->getMobile();
        $client->address = $resiliationClient->getAddress()->getName();
        $client->zipCode = $resiliationClient->getAddress()->getZipCode();
        $client->city = $resiliationClient->getAddress()->getCity();

        $result = new stdClass();
        $result->service = $service;
        $result->client = $client;
        $result->type = $resiliation->getType();
        $result->number = $resiliation->getNumber();
        $result->description = $resiliation->getDescription();
        $result->createdAt = $resiliation->getCreatedAt();
        $result->customId = $resiliation->getCustomId();
        $result->generatedResiliationPath = $resiliation->getGeneratedResiliationPath();
        $result->generatedResiliationPathFile = $resiliation->getGeneratedResiliationPathFile();
        $result->generatedPreviewPathFile = $resiliation->getGeneratedPreviewPathFile();

        return $this->json($result, 200, [], []);
    }

    /**
     * @Route("/api/resiliation", name="api_resiliation_store", methods={"POST"})
     */
    public function resiliationStore(
        Request $request,
        ServiceRepository $serviceRepository,
        ClientManager $clientManager,
        ClientRepository $clientRepository,
        SerializerInterface $serializerInterface,
        ResiliationManager $resiliationManager
    ) {
        $resiliation = json_decode($request->getContent(), true);
        try {
            $service = $serviceRepository->findOneBySlug($resiliation["service"]["slug"]);
            $resiliation["service"] = "/apip/services/" . $service->getId();

            $client = $resiliation["client"];
            $clientAddress = $resiliation["client"]["address"];

            $storeClient = $clientManager->init();
            $storeClient->setFirstName($client["firstName"]);
            $storeClient->setLastName($client["lastName"]);
            $storeClient->setMobile($client["mobile"]);
            $storeAddress = new Address();
            $storeAddress->setName($clientAddress["name"]);
            $storeAddress->setComplement($clientAddress["complement"]);
            $storeAddress->setZipCode($clientAddress["zipCode"]);
            $storeAddress->setCity($clientAddress["city"]);
            $storeClient->setAddress($storeAddress);
            $clientManager->save($storeClient);

            $clientlatest = $clientRepository->findOneBy([], ['id' => 'DESC']);

            $resiliation["client"] = "/apip/clients/" . $clientlatest->getId();

            $resiliation = $serializerInterface->deserialize(json_encode($resiliation), Resiliation::class, 'json', []);
            $resiliation->setCreatedAt(new \DateTime());
            $resiliation->setCustomId(new Ulid());

            $resiliationManager->save($resiliation);
            
            return $this->json($resiliation, 201, [], []);
        } catch (NotEncodableValueException $e) {
            return $this->json([
                "status"=>400,
                "message"=>$e->getMessage()
            ], 400);
        }
    }

    /**
     * @Route("/api/resiliation/{customId}", name="api_resiliation_edit", methods={"PATCH"})
     */
    public function resiliationEdit(
        Request $request,
        $customId,
        ResiliationRepository $resiliationRepository,
        ResiliationManager $resiliationManager,
        ServiceRepository $serviceRepository,
        ClientManager $clientManager
    )
    {
        $request = json_decode($request->getContent(), true);

        $service = $serviceRepository->findOneBySlug($request["service"]["slug"]);
        $resiliation = $resiliationRepository->findOneByCustomId($customId);
        $resiliationClient = $resiliation->getClient();
        $resiliationClientAddress = $resiliationClient->getAddress();

        $resiliation->setService($service);

        $client = $request["client"];

        $resiliationClient->setFirstName($client["firstName"]);
        $resiliationClient->setLastName($client["lastName"]);
        $resiliationClient->setMobile($client["mobile"]);

        $resiliationClientAddress->setName($client["address"]["name"]);
        $resiliationClientAddress->setZipCode($client["address"]["zipCode"]);
        $resiliationClientAddress->setCity($client["address"]["city"]);

        $resiliationClient->setAddress($resiliationClientAddress);

        $clientManager->save($resiliationClient);
        
        $resiliation->setNumber($request["number"]);
        $resiliation->setType($request["type"]);
        $resiliation->setDescription($request["description"]);
        $resiliation->setCreatedAt(new \DateTime());

        $resiliationManager->save($resiliation);

        return $this->json($resiliation, 201, [], []);
    }
}
