<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Profile;
use App\Repository\Traits\RepositoryUuidFinder;
use App\Repository\Traits\Transactional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;

/**
 * @method Profile|null find($id, $lockMode = null, $lockVersion = null)
 * @method Profile|null findOneBy(array $criteria, array $orderBy = null)
 * @method Profile[]    findAll()
 * @method Profile[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProfileRepository extends ServiceEntityRepository
{
    use Transactional;
    use RepositoryUuidFinder;

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
        // @phpstan-ignore-next-line
        $profile = $this->getEntityManager()->getReference($this->getClassName(), $id);

        if ($profile) {
            $this->getEntityManager()->remove($profile);
            $this->getEntityManager()->flush();
        }
    }

    /**
     * @param Profile $profile
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(Profile $profile): void
    {
        $this->getEntityManager()->persist($profile);
        $this->getEntityManager()->flush();
    }
}
