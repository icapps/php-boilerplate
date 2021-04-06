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

    // /**
    //  * @return ApiPlatformExample[] Returns an array of ApiPlatformExample objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiPlatformExample
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
