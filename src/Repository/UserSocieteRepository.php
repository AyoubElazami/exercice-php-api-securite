<?php
namespace App\Repository;

use App\Entity\UserSociete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class UserSocieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserSociete::class);
    }

    public function findByUserAndSociete($userId, $societeId)
    {
        return $this->createQueryBuilder('us')
            ->where('us.user = :userId')
            ->andWhere('us.societe = :societeId')
            ->setParameter('userId', $userId)
            ->setParameter('societeId', $societeId)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
