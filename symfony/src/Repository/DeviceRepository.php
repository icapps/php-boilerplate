<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Device;
use App\Repository\Traits\RepositoryUuidFinder;
use App\Repository\Traits\Transactional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Device|null find($id, $lockMode = null, $lockVersion = null)
 * @method Device|null findOneBy(array $criteria, array $orderBy = null)
 * @method Device[]    findAll()
 * @method Device[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeviceRepository extends ServiceEntityRepository
{
    use Transactional;
    use RepositoryUuidFinder;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /**
     * @return Device
     */
    public function create(): Device
    {
        return new Device();
    }

    /**
     * @param int $id
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(int $id): void
    {
        $device = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id
        );

        $this->getEntityManager()->remove($device);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Device $device
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Device $device): void
    {
        $this->getEntityManager()->persist($device);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     *
     * @return Device|null
     */
    public function findById(int $id): ?Device
    {
        $device = $this->find($id);

        if (!$device) {
            return null;
        }

        return $device;
    }
}
