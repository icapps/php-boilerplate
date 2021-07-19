<?php

namespace App\User\Infrastructure\Repository;

use App\User\Domain\Repository\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Doctrine\User;
use App\User\Infrastructure\Repository\Traits\Transactional;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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
class UserRepository extends ServiceEntityRepository implements UserRepositoryInterface, PasswordUpgraderInterface
{
    use Transactional;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * {@inheritDoc}
     */
    public function create(): User
    {
        return new User();
    }

    /**
     * {@inheritDoc}
     */
    public function store(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function remove(User $user): void
    {
        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * {@inheritDoc}
     */
    public function findUserByEmail(Email $email): ?User
    {
        $user = $this->findOneBy([
            'email' => $email,
        ]);

        if (!$user) {
            return null;
        }

        return $user;
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param UserInterface $user
     * @param string $newHashedPassword
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->store($user);
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
}
