<?php

namespace App\User\Application\Command\PasswordReset;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\User\Application\Command\Dto\PasswordResetInput;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\Security\Core\Security;

/**
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class PasswordResetDataPersister implements DataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private Security $security
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof PasswordResetInput;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        /** @var PasswordResetInput $data */
        $this->commandBus->handle(new PasswordResetCommand(
            Email::fromString($data->email)
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data)
    {
        // this method just need to be presented
    }
}
