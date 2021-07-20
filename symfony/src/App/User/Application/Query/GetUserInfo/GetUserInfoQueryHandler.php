<?php

declare(strict_types=1);

namespace App\User\Application\Query\GetUserInfo;

use App\Shared\Infrastructure\Bus\Query\QueryHandlerInterface;
use App\User\Application\Query\Dto\UserProfileOutput;
use App\User\Infrastructure\Repository\ProfileRepository;
use App\User\Infrastructure\Repository\UserRepository;

final class GetUserInfoQueryHandler implements QueryHandlerInterface
{

    public function __construct(
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository
    ) {
        //
    }

    /**
     * @param GetUserInfoQuery $query
     * @return UserProfileOutput
     */
    public function __invoke(GetUserInfoQuery $query): UserProfileOutput
    {
        $user = $this->userRepository->find($query->id);

        return UserProfileOutput::fromEntity($user);
    }
}
