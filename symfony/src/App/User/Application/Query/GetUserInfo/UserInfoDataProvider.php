<?php

namespace App\User\Application\Query\GetUserInfo;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Shared\Infrastructure\Bus\Query\QueryBus;
use App\User\Application\Query\FindUserById\FindUserByIdQuery;
use App\User\Domain\Exception\ForbiddenException;
use App\User\Domain\Exception\NotFoundException;
use Ui\Http\Rest\ApiResource\User\User;
use App\User\Application\Query\Dto\UserProfileOutput;
use App\User\Infrastructure\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

/**
 * @link: https://api-platform.com/docs/core/data-providers.
 */
final class UserInfoDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private UserRepository $userRepository,
        private Security $security,
        private QueryBus $queryBus
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === User::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?UserProfileOutput
    {
        // Load user.
        if (!$user = $this->queryBus->ask(new FindUserByIdQuery($id))) {
            throw new NotFoundException('User not found');
        }

        // Check access.
        if ($this->security->getUser() !== $user) {
            throw new ForbiddenException('User not found');
        }

        return $this->queryBus->ask(new GetUserInfoQuery($id));
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        // @TODO:: query + handler.
        return $this->userRepository->findAll();
    }
}
