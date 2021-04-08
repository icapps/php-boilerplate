<?php

namespace App\Repository;

use App\Entity\ApiPlatformExample;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiPlatformExample|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiPlatformExample|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiPlatformExample[]    findAll()
 * @method ApiPlatformExample[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiPlatformExampleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiPlatformExample::class);
    }
}
