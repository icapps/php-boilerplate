<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Repository\ProfileRepositoryInterface;
use App\User\Infrastructure\Doctrine\Profile;
use App\User\Infrastructure\Repository\Traits\Transactional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 *
 * @extends ServiceEntityRepository<Location>
 */
class ProfileRepository extends ServiceEntityRepository implements ProfileRepositoryInterface
{
    use Transactional;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }


    /**
     * {@inheritDoc}
     */
    public function create(): Profile
    {
        return new Profile();
    }

    /**
     * {@inheritDoc}
     */
    public function store(Profile $profile): void
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function remove(Profile $profile): void
    {
        $this->getEntityManager()->remove($profile);
        $this->getEntityManager()->flush();
    }

    /**
     * @param int $id
     * @return Profile|null
     */
    public function findById(int $id): ?Profile
    {
        $profile = $this->find($id);

        if (!$profile) {
            return null;
        }

        return $profile;
    }

    /**
     * @return Profile|null
     */
    public function findLatest(): ?Profile
    {
        return $this->findOneBy([], ['id' => 'DESC']);
    }
}
