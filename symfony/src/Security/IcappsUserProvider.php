<?php


namespace App\Security;


use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\PayloadAwareUserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

abstract class IcappsUserProvider implements PayloadAwareUserProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function loadUserByUsername(string $username): UserInterface
    {
        return $this->loadUserByUsernameAndPayload($username, []);
    }

    /**
     * {@inheritDoc}
     */
    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    /**
     * {@inheritDoc}
     */
    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
