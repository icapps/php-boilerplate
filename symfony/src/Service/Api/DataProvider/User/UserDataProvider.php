<?php

namespace App\Service\Api\DataProvider\User;

use ApiPlatform\Core\DataProvider\ContextAwareCollectionDataProviderInterface;
use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Dto\UserProfileDto;
use App\Entity\User;
use App\Repository\ProfileRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

/**
 * Class UserDataProvider
 *
 * This is a custom DataProvider for which getItem and getCollection can be customized to retrieve data.
 * More information: https://api-platform.com/docs/core/data-providers.
 *
 * @package App\Service\Api\DataProvider\Examples
 */
final class UserDataProvider implements ItemDataProviderInterface, ContextAwareCollectionDataProviderInterface, RestrictedDataProviderInterface
{
    /**
     * {@inheritDoc}
     */
    public function __construct(
        private UserRepository $userRepository,
        private ProfileRepository $profileRepository,
        private Security $security
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
    public function getItem(string $resourceClass, $id, string $operationName = null, array $context = []): ?UserProfileDto
    {
        // Load user.
        $user = $this->userRepository->find($id);

        // @TODO:: unify security checks?
        // Check user + access.
        if (!$user || $this->security->getUser() !== $user) {
            return null;
        }

        // Prepare DTO.
        $data = new UserProfileDto();

        // Get user profile.
        $profile = $this->profileRepository->find($user->getProfileId());

        $data->firstName = $profile->getFirstName();
        $data->lastName = $profile->getLastName();
        $data->email = $user->getEmail();
        $data->language = $user->getLanguage();

        return $data;
    }

    /**
     * {@inheritDoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null, array $context = []): iterable
    {
        return $this->userRepository->findAll();
    }
}
