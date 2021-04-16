<?php

namespace App\Repository;

use App\Entity\User;
use App\Repository\Traits\Transactional;
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
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    use Transactional;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @return User
     */
    public function create(): User
    {
        return new User();
    }

    /**
     * @param int $id
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(int $id): void
    {
        $user = $this->getEntityManager()->getReference(
            $this->getClassName(),
            $id
        );

        $this->getEntityManager()->remove($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @param User $user
     *
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function save(User $user): void
    {
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
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
}
