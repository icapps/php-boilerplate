<?php

namespace App\User\Application\Command\Logout;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\User\Application\Command\Dto\LogoutInput;
use App\User\Domain\Exception\ForbiddenException;
use Symfony\Component\Security\Core\Security;

/**
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class LogoutDataPersister implements DataPersisterInterface
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
        return $data instanceof LogoutInput;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        if (!$user = $this->security->getUser()) {
            throw new ForbiddenException('JWT Token not found');
        }

        /** @var LogoutInput $data */
        $this->commandBus->handle(new LogoutCommand(
            $user,
            $data->deviceId
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
