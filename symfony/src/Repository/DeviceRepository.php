<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Device;
use App\Repository\Model\AbstractRepositoryFunctionsInterface;
use App\Repository\Traits\AbstractRepositoryFunctions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Location>
 */
class DeviceRepository extends ServiceEntityRepository implements AbstractRepositoryFunctionsInterface
{
    use AbstractRepositoryFunctions;
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }
}
