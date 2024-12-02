<?php

namespace App\Repository;

use App\Entity\Projet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Projet::class);
    }

    
    public function findByTitre(string $titre): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.titre = :titre')
            ->setParameter('titre', $titre)
            ->getQuery()
            ->getResult();
    }

    public function findBySociete(int $societeId): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.societe = :societeId')
            ->setParameter('societeId', $societeId)
            ->getQuery()
            ->getResult();
    }

    // Ajoutez d'autres méthodes personnalisées ici si nécessaire
}
