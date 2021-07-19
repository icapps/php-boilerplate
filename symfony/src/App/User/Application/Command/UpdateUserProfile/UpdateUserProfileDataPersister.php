<?php

namespace App\User\Application\Command\UpdateUserProfile;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Shared\Infrastructure\Bus\Command\CommandBus;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use App\User\Application\Query\FindUserById\FindUserByIdQuery;
use App\User\Application\Command\Dto\UserProfileInput;
use App\User\Application\Query\Dto\UserProfileOutput;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Security;

/**
 * @link: https://api-platform.com/docs/core/data-persisters.
 */
final class UpdateUserProfileDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private UserRepository $userRepository,
        private QueryBus $queryBus,
        private Security $security
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof UserProfileInput;
    }

    /**
     * {@inheritDoc}
     */
    public function persist($data, array $context = [])
    {
        /** @var UserProfileOutput $current */
        $userCurrentProfile = $context['previous_data'];

        // Check existence.
        if (!$user = $this->queryBus->ask(new FindUserByIdQuery($userCurrentProfile->id))) {
            throw new NotFoundHttpException('User not found');
        }

        // @TODO:: reimplement security.
        // Check access.
        // if ($this->security->getUser() !== $user) {
        //    throw new ForbiddenException();
        // }

        /** @var UserProfileInput $data */
        $this->commandBus->handle(new UpdateUserProfileCommand(
            $userCurrentProfile->id,
            $data->language,
            $data->firstName,
            $data->lastName
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function remove($data, array $context = [])
    {
        // this method just need to be presented
    }
}
