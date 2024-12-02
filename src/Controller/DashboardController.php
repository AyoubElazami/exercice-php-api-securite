<?php

    
namespace App\Controller;

use App\Repository\UserSocieteRepository;
use App\Repository\ProjetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    public function index(
        UserSocieteRepository $userSocieteRepository,
        ProjetRepository $projetRepository
    ): Response {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder au dashboard.');
        }

        $userSocietes = $userSocieteRepository->findBy(['user' => $user]);

        $societes = [];
        $projets = [];
        $roles = [];

        foreach ($userSocietes as $userSociete) {
            $societe = $userSociete->getSociete();
            $societes[] = $societe;

            $projets[$societe->getId()] = $projetRepository->findBy(['societe' => $societe]);

            $roles[$societe->getId()] = $userSociete->getRole();
        }

        return $this->render('dashboard.html.twig', [
            'societes' => $societes,
            'projets' => $projets,
            'roles' => $roles,
        ]);
    }
}
