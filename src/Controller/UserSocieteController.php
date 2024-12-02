<?php

namespace App\Controller;

use App\Entity\UserSociete;
use App\Repository\UserSocieteRepository;
use App\Repository\UserRepository;
use App\Repository\SocieteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;

class UserSocieteController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/user/{userId}/societe/{societeId}/role', methods: ['POST'])]
    public function addRoleToUserInSociete(
        int $userId,
        int $societeId,
        Request $request,
        UserSocieteRepository $userSocieteRepository,
        UserRepository $userRepository,
        SocieteRepository $societeRepository
    ): Response {
        $user = $userRepository->find($userId);
        $societe = $societeRepository->find($societeId);

        if (!$user || !$societe) {
            return new JsonResponse(['message' => 'Utilisateur ou société non trouvé'], Response::HTTP_NOT_FOUND);
        }

        $role = $request->get('role');
        if (!in_array($role, ['ROLE_USER', 'ROLE_ADMIN', 'ROLE_MANAGER'])) {
            return new JsonResponse(['message' => 'Rôle invalide'], Response::HTTP_BAD_REQUEST);
        }

        $existingRole = $userSocieteRepository->findByUserAndSociete($userId, $societeId);
        if ($existingRole) {
            return new JsonResponse(['message' => 'Rôle déjà attribué'], Response::HTTP_CONFLICT);
        }

        $userSociete = new UserSociete();
        $userSociete->setUser($user);
        $userSociete->setSociete($societe);
        $userSociete->setRole($role);

        $this->entityManager->persist($userSociete);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Rôle ajouté avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/user/{userId}/societe/{societeId}/role', methods: ['DELETE'])]
    public function removeRoleFromUserInSociete(
        int $userId,
        int $societeId,
        UserSocieteRepository $userSocieteRepository
    ): Response {
        $userSociete = $userSocieteRepository->findByUserAndSociete($userId, $societeId);

        if (!$userSociete) {
            return new JsonResponse(['message' => 'Relation non trouvée'], Response::HTTP_NOT_FOUND);
        }

        $this->entityManager->remove($userSociete);
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Rôle supprimé avec succès'], Response::HTTP_OK);
    }

    #[Route('/user/{userId}/societes', methods: ['GET'])]
    public function getUserSocietes(
        int $userId,
        UserSocieteRepository $userSocieteRepository
    ): Response {
        $userSocietes = $userSocieteRepository->findBy(['user' => $userId]);

        $result = [];
        foreach ($userSocietes as $userSociete) {
            $result[] = [
                'societe' => $userSociete->getSociete()->getNom(),
                'role' => $userSociete->getRole()
            ];
        }

        return new JsonResponse($result, Response::HTTP_OK);
    }
}
