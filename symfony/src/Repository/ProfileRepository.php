<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Profile;
use App\Repository\Model\AbstractRepositoryFunctionsInterface;
use App\Repository\Model\TransactionalInterface;
use App\Repository\Traits\AbstractRepositoryFunctions;
use App\Repository\Traits\Transactional;
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
class ProfileRepository extends ServiceEntityRepository implements AbstractRepositoryFunctionsInterface, TransactionalInterface
{
    use Transactional;
    use AbstractRepositoryFunctions;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Profile::class);
    }
}
