<?php

namespace App\Service\Api\DataProvider\User;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\ApiResource\User\User as UserApiResource;
use App\Entity\User;
use App\Dto\User\UserProfileDto;
use App\Exception\UserNotFoundException;
use App\Repository\UserRepository;
use App\Utils\UuidEncoder;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDataProvider
 *
 * @link: https://api-platform.com/docs/core/data-providers.
 */
final class UserDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private UserRepository $userRepository,
        private Security $security
    ) {
        //
    }

    /**
     * {@inheritDoc}
     */
    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return $resourceClass === UserApiResource::class;
    }

    /**
     * {@inheritDoc}
     */
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?UserProfileDto
    {
        // Load user.
        /** @var string $id */
        if (!$user = $this->userRepository->findByEncodedUuid($id)) {
            throw new NotFoundHttpException('User not found', null, 404);
        }

        // Check access.
        if ($this->security->getUser() !== $user) {
            throw new AuthenticationException();
        }

        // Get user profile.
        /** @var User $user */
        $profile = $user->getProfile();

        if (!$profile) {
            throw new UserNotFoundException('User profile not found');
        }

        // Create output.
        $output = new UserProfileDto();
        $output->userSid = UuidEncoder::encode($user->getUuid());
        $output->firstName = $profile->getFirstName();
        $output->lastName = $profile->getLastName();
        $output->email = $user->getEmail();
        $output->language = $user->getLanguage();

        return $output;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        return $this->userRepository->findAll();
    }
}
