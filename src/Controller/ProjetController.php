<?php

namespace App\Controller;

use App\Repository\SocieteRepository;
use App\Repository\UserSocieteRepository;


use App\Entity\Projet;
use App\Entity\Societe;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProjetController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private SerializerInterface $serializer;

    public function __construct(EntityManagerInterface $entityManager, SerializerInterface $serializer)
    {
        $this->entityManager = $entityManager;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/projets", name="projet_list", methods={"GET"})
     */
    public function list(): JsonResponse
    {
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();

        if (!$projets) {
            return new JsonResponse(['message' => 'Aucun projet trouvé.'], 404);
        }

        $data = $this->serializer->serialize($projets, 'json', ['groups' => 'projet']);
        return new JsonResponse($data, 200, [], true);
    }

    /**
     * @Route("/projets/page", name="projet_list_page", methods={"GET"})
     */
    public function listPage(Request $request): Response
    {
        $projets = $this->entityManager->getRepository(Projet::class)->findAll();

        return $this->render('projet/list.html.twig', [
            'projets' => $projets,
        ]);
    }

    /**
     * @Route("/projet/new", name="projet_create", methods={"POST"})
     */

    
     
    
     
       /**
 * @Route("/projet/create", name="projet_create", methods={"POST"})
 */
public function create(
    Request $request,
    EntityManagerInterface $entityManager,
    SocieteRepository $societeRepository,
    UserSocieteRepository $userSocieteRepository
): JsonResponse {
    // Récupérer l'utilisateur connecté
    $user = $this->getUser();

    if (!$user) {
        return new JsonResponse(['message' => 'Vous devez être connecté.'], Response::HTTP_UNAUTHORIZED);
    }

    // Récupérer les données envoyées via le formulaire ou JSON
    $data = $request->request->all(); // Utilisé pour les requêtes `application/x-www-form-urlencoded`
    if (empty($data)) {
        $data = json_decode($request->getContent(), true); // Utilisé pour les requêtes JSON
    }

    $societeId = $data['societeId'] ?? null;
    $titre = $data['titre'] ?? null;
    $description = $data['description'] ?? null;

    // Validation des champs
    if (!$societeId || !$titre || !$description) {
        return new JsonResponse(['message' => 'Tous les champs sont obligatoires.'], Response::HTTP_BAD_REQUEST);
    }

    // Récupérer la société
    $societe = $societeRepository->find($societeId);
    if (!$societe) {
        return new JsonResponse(['message' => 'Société non trouvée.'], Response::HTTP_NOT_FOUND);
    }

    // Vérifier si l'utilisateur est manager de cette société
    $userSociete = $userSocieteRepository->findOneBy(['user' => $user, 'societe' => $societe]);
    if (!$userSociete || $userSociete->getRole() !== 'ROLE_MANAGER') {
        return new JsonResponse(['message' => 'Accès refusé. Vous devez être manager pour créer un projet.'], Response::HTTP_FORBIDDEN);
    }

    // Créer un nouveau projet
    $projet = new Projet();
    $projet->setTitre($titre);
    $projet->setDescription($description);
    $projet->setSociete($societe);
    $projet->setDateCreation(new \DateTime());

    // Sauvegarder le projet dans la base de données
    $entityManager->persist($projet);
    $entityManager->flush();

    return new JsonResponse(['message' => 'Projet créé avec succès !'], Response::HTTP_CREATED);
}

     
     
    /**
     * @Route("/projet/{id}", name="projet_update", methods={"PUT"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $projet = $this->entityManager->getRepository(Projet::class)->find($id);

        if (!$projet) {
            return new JsonResponse(['error' => 'Projet non trouvé.'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $projet->setTitre($data['titre'] ?? $projet->getTitre());
        $projet->setDescription($data['description'] ?? $projet->getDescription());

        if (isset($data['societeId'])) {
            $societe = $this->entityManager->getRepository(Societe::class)->find($data['societeId']);
            if ($societe) {
                $projet->setSociete($societe);
            }
        }

        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Projet mis à jour avec succès !'], 200);
    }

    /**
     * @Route("/projet/{id}", name="projet_delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $projet = $this->entityManager->getRepository(Projet::class)->find($id);

        if (!$projet) {
            return new JsonResponse(['error' => 'Projet non trouvé.'], 404);
        }

        $this->entityManager->remove($projet);
        $this->entityManager->flush();

        return new JsonResponse(['status' => 'Projet supprimé avec succès !'], 200);
    }

    /**
 * @Route("/projet/{id}/edit", name="projet_update_page", methods={"GET"})
 */
public function editPage(int $id): Response
{
    $projet = $this->entityManager->getRepository(Projet::class)->find($id);

    if (!$projet) {
        throw $this->createNotFoundException('Projet non trouvé.');
    }

    return $this->render('projet/edit.html.twig', [
        'projet' => $projet,
    ]);
}


    /**
     * @Route("/projet/{id}/page", name="projet_show_page", methods={"GET"})
     */
    public function showPage(int $id): Response
    {
        $projet = $this->entityManager->getRepository(Projet::class)->find($id);

        if (!$projet) {
            throw $this->createNotFoundException('Projet non trouvé.');
        }

        return $this->render('projet/show.html.twig', [
            'projet' => $projet,
        ]);
    }
}
