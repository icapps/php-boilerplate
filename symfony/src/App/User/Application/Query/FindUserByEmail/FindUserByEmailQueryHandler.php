<?php

declare(strict_types=1);

namespace App\User\Application\Query\FindUserByEmail;

use App\Shared\Infrastructure\Bus\Query\QueryHandlerInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Doctrine\User;
use App\User\Infrastructure\Repository\UserRepository;

final class FindUserByEmailQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {
        //
    }

    /**
     * @param FindUserByEmailQuery $query
     */
    public function __invoke(FindUserByEmailQuery $query): ?User
    {
        return $this->userRepository->findUserByEmail(Email::fromString($query->email));
    }
}
