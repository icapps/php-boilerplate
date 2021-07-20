<?php

declare(strict_types=1);

namespace App\User\Application\Query\FindUserById;

use App\Shared\Infrastructure\Bus\Query\QueryHandlerInterface;
use App\User\Infrastructure\Doctrine\User;
use App\User\Infrastructure\Repository\UserRepository;

final class FindUserByIdQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {
        //
    }

    /**
     * @param FindUserByIdQuery $query
     *
     * @return User|null
     */
    public function __invoke(FindUserByIdQuery $query): ?User
    {
        return $this->userRepository->find($query->id);
    }
}
