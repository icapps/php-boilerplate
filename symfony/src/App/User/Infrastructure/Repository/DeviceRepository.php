<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Repository\DeviceRepositoryInterface;
use App\User\Infrastructure\Doctrine\Device;
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
class DeviceRepository extends ServiceEntityRepository implements DeviceRepositoryInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    /**
     * {@inheritDoc}
     */
    public function create(): Device
    {
        return new Device();
    }

    /**
     * {@inheritDoc}
     */
    public function store(Device $device): void
    {
        $this->getEntityManager()->persist($device);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Device $device): void
    {
        $this->getEntityManager()->remove($device);
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
