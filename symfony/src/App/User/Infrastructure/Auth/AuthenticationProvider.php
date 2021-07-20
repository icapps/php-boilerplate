<?php

declare(strict_types=1);

namespace App\User\Infrastructure\Auth;

use App\User\Domain\Exception\InvalidPayloadException;
use App\User\Domain\Exception\NotFoundException;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Doctrine\User;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Webmozart\Assert\InvalidArgumentException;

final class AuthenticationProvider implements UserProviderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByIdentifier(string $identifier): User
    {
        try {
            return $this->userRepository->findUserByEmail(Email::fromString($identifier));
        } catch (InvalidArgumentException $exception) {
            throw new InvalidPayloadException(\sprintf('Invalid email "%s".', $identifier));
        } catch (NotFoundException $exception) {
            throw new NotFoundException(\sprintf('User "%s" not found.', $identifier));
        }
    }

    public function loadUserByUsername(string $username): User
    {
        try {
            return $this->userRepository->findUserByEmail(Email::fromString($username));
        } catch (InvalidArgumentException $exception) {
            throw new InvalidPayloadException(\sprintf('Invalid email "%s".', $username));
        } catch (NotFoundException $exception) {
            throw new NotFoundException(\sprintf('User "%s" not found.', $username));
        }
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        return $user;
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }
}
