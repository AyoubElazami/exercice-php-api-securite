<?php

namespace App\Controller;

use App\Entity\Societe;
use App\Repository\SocieteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class SocieteController extends AbstractController
{
    private SocieteRepository $societeRepository;
    private SerializerInterface $serializer;

    public function __construct(SocieteRepository $societeRepository, SerializerInterface $serializer)
    {
        $this->societeRepository = $societeRepository;
        $this->serializer = $serializer;
    }

    
    public function list(): JsonResponse
    {
        $societes = $this->societeRepository->findAll();
        $data = $this->serializer->serialize($societes, 'json', ['groups' => 'societe']);

        return new JsonResponse($data, 200, [], true);
    }

    
    public function listPage(): \Symfony\Component\HttpFoundation\Response
    {
        $societes = $this->societeRepository->findAll();
    
        return $this->render('societes/indexx.html.twig', [
            'societes' => $societes
        ]);
    }
    /**
     * @Route("/societe", name="societe_create", methods={"POST"})
     */
    public function create(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $societe = new Societe();
        $societe->setNom($data['nom']);
        $societe->setSiret($data['siret']);
        $societe->setAdresse($data['adresse']);

        $entityManager->persist($societe);
        $entityManager->flush();

        return new JsonResponse(['status' => 'Société créée!'], 201);
    }

    /**
     * @Route("/societe/{id}", name="societe_show", methods={"GET"})
     */
    public function show($id): \Symfony\Component\HttpFoundation\Response
    {
        $societe = $this->societeRepository->find($id);

        if (!$societe) {
            throw $this->createNotFoundException('Société non trouvée');
        }

        // Rendu du template Twig avec les informations de la société
        return $this->render('societe/show.html.twig', [
            'societe' => $societe
        ]);
    }
}
