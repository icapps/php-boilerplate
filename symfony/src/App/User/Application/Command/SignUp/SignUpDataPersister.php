<?php

namespace App\User\Application\Command\SignUp;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use App\User\Application\Command\Dto\SignUpInput;
use App\User\Application\Query\FindUserByEmail\FindUserByEmailQuery;
use App\User\Domain\Exception\EmailAlreadyExistException;
use App\User\Infrastructure\Repository\UserRepository;

/**
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class SignUpDataPersister implements DataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private UserRepository $userRepository,
        private QueryBus $queryBus
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data): bool
    {
        return $data instanceof SignUpInput;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data)
    {
        if ($this->queryBus->ask(new FindUserByEmailQuery($data->email))) {
            throw new EmailAlreadyExistException();
        }

        $this->commandBus->handle(new SignUpCommand(
            $data->firstName,
            $data->lastName,
            $data->email,
            $data->language,
            $data->password
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
