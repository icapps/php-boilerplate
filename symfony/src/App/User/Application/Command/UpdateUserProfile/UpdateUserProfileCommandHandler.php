<?php

declare(strict_types=1);

namespace App\User\Application\Command\UpdateUserProfile;

use App\Shared\Infrastructure\Bus\Command\CommandHandlerInterface;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use App\User\Domain\Exception\NotFoundException;
use App\User\Infrastructure\Repository\ProfileRepository;
use App\User\Infrastructure\Repository\UserRepository;

final class UpdateUserProfileCommandHandler implements CommandHandlerInterface
{

    public function __construct(
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
        private QueryBus $queryBus
    ) {
        //
    }

    public function __invoke(UpdateUserProfileCommand $command): void
    {
        if (!$user = $this->userRepository->find($command->id)) {
            throw new NotFoundException('User not found');
        }

        // Get user profile.
        $profile = $user->getProfile();

        // Update user.
        $user->setLanguage($command->language);
        $this->userRepository->store($user);

        // Update profile.
        $profile->setFirstName($command->firstName);
        $profile->setLastName($command->lastName);
        $this->profileRepository->store($profile);
    }
}
