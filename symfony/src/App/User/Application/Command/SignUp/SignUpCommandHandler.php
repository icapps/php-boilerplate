<?php

declare(strict_types=1);

namespace App\User\Application\Command\SignUp;

use ApiPlatform\Core\Validator\Exception\ValidationException;
use ApiPlatform\Core\Validator\ValidatorInterface;
use App\Shared\Infrastructure\Bus\Command\CommandHandlerInterface;
use App\User\Infrastructure\Doctrine\Profile;
use App\User\Infrastructure\Repository\ProfileRepository;
use App\User\Infrastructure\Repository\UserRepository;
use App\User\Infrastructure\Doctrine\User;

final class SignUpCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
        private ValidatorInterface $validator
    ) {
        //
    }

    public function __invoke(SignUpCommand $command): void
    {
        // Create user.
        $user = User::create(
            $command->email,
            $command->language,
            $command->password
        );

        // Create profile.
        $profile = Profile::create(
            $command->firstName,
            $command->lastName
        );

        // Validate profile.
        $context['groups'] = 'orm-registration';
        $this->validator->validate($profile, $context);

        // Validate and save user + profile.
        $this->profileRepository->beginTransaction();
        $this->userRepository->beginTransaction();
        try {
            // Save + set user profile.
            $this->profileRepository->store($profile);
            $user->setProfile($profile);

            // Validate user.
            $context['groups'] = 'orm-registration';
            $this->validator->validate($user, $context);

            // Save user.
            $this->userRepository->store($user);

            // Commit changes.
            $this->profileRepository->commit();
            $this->userRepository->commit();
        } catch (ValidationException $exception) {
            $this->profileRepository->rollback();
            $this->userRepository->rollback();
            throw $exception;
        }

        // @TODO:: activation mail.
    }
}
