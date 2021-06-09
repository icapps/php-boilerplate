<?php

declare(strict_types=1);

namespace App\Repository;

use App\Component\Model\ProfileInterface;
use App\Entity\Profile;
use App\Repository\Model\ProfileRepositoryInterface;
use App\Repository\Model\TransactionalInterface;
use App\Repository\Traits\Transactional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use http\Exception\InvalidArgumentException;

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
     * @return Profile
     */
    public function create(): Profile
    {
        return new Profile();
    }

    /**
     * @param int $id
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(int $id): void
    {
        $profile = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id
        );

        $this->getEntityManager()->remove($profile);
        $this->getEntityManager()->flush();
    }

    /**
     * @param Profile $profile
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(ProfileInterface $profile): void
    {
        // Check type explicitly
        if (!($profile instanceof Profile)) {
            throw new InvalidArgumentException();
        }
        $this->getEntityManager()->persist($profile);
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
