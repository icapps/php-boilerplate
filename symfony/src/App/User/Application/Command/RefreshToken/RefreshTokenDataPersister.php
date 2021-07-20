<?php

namespace App\User\Application\Command\RefreshToken;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\User\Application\Command\Dto\RefreshTokenInput;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class RefreshTokenDataPersister implements DataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private RequestStack $requestStack
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof RefreshTokenInput;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        return $this->commandBus->handleAndReturnResult(new RefreshTokenCommand(
            $this->requestStack->getCurrentRequest()
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
