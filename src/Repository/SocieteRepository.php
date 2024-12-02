<?php

namespace App\Repository;

use App\Entity\Societe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class SocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Societe::class);
    }

     public function findById(int $id): ?Societe
    {
        return $this->find($id);  // Simple appel à la méthode de base de Doctrine
    }

    
    public function findByNom(string $nom): array
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.nom = :nom')
            ->setParameter('nom', $nom)
            ->getQuery()
            ->getResult();
    }

}
