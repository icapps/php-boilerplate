<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Model\BaseRepositoryFunctionsInterface;
use App\Repository\Traits\BaseRepositoryFunctionsTrait;
use App\Repository\Traits\TransactionalTrait;
use App\Utils\ProfileHelper;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface, BaseRepositoryFunctionsInterface
{
    use TransactionalTrait;
    use BaseRepositoryFunctionsTrait;

    public function __construct(ManagerRegistry $registry, private ProfileHelper $profileHelper)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     * @param UserInterface $user
     * @param string $newEncodedPassword
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Find user by activation token.
     *
     * @param string $activationToken
     *
     * @return User|null
     */
    public function findByActivationToken(string $activationToken): ?User
    {
        $user = $this->findOneBy([
            'activationToken' => $activationToken,
        ]);

        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * Find user by reset token.
     *
     * @param string $resetToken
     *
     * @return User|null
     */
    public function findByResetToken(string $resetToken): ?User
    {
        $user = $this->findOneBy([
            'resetToken' => $resetToken,
        ]);

        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * @param int $id
     */
    public function remove(int $id): void
    {
        /**
         * @var User $user
         */
        $user = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id
        );

        $this->getEntityManager()->remove($user);

        // Remove User Profile
        $this->profileHelper->getProfileRepository($user)->remove($user->getProfileId());

        $this->getEntityManager()->flush();
    }
}
