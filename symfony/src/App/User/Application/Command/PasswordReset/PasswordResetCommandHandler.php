<?php

declare(strict_types=1);

namespace App\User\Application\Command\PasswordReset;

use App\Shared\Infrastructure\Bus\Command\CommandHandlerInterface;
use App\User\Infrastructure\Repository\UserRepository;
use Ramsey\Uuid\Uuid;

final class PasswordResetCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepository $userRepository
    ) {
        //
    }

    public function __invoke(PasswordResetCommand $command): void
    {
        // Get user.
        $user = $this->userRepository->findUserByEmail($command->email);

        // Set reset token.
        $user->setResetToken(Uuid::uuid4()->toString());

        // Save.
        $this->userRepository->store($user);

        // @TODO:: mail.
        // @TODO:: log.
    }
}
